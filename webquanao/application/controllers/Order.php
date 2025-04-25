<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->config->load('vnpay'); // Load cấu hình VNPAY
	}

	// Helper function to check if the request is AJAX
	private function input_is_ajax_request() {
		return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}

	public function index()
	{
		$carts = $this->cart->contents();

		if (empty($carts)) {
			redirect(base_url('/'));
			return;
		}

		$total_amount = 0;
		foreach ($carts as $value) {
			$total_amount = $total_amount + $value['subtotal'];
		}
		$this->data['total_amount'] = $total_amount;

		// Make sure user data is explicitly set
		$user = $this->session->userdata('user');
		$this->data['user'] = $user;

		$this->data['temp'] = 'site/order/index.php';
		$this->load->view('site/layoutsub', $this->data);
	}

	// Khởi tạo thanh toán
	public function payment()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			header('Content-Type: application/json;');

			$carts = $this->cart->contents();

			if (empty($carts)) {
				echo json_encode(["status" => "error", "message" => "Giỏ hàng trống"], JSON_UNESCAPED_UNICODE);
				return;
			}
			$total_amount = 0;
			foreach ($carts as $value) {
				$total_amount = $total_amount + $value['subtotal'];
			}

			// Nhận dữ liệu JSON từ request
			$data = json_decode(file_get_contents("php://input"), true);

			// Kiểm tra nếu không có dữ liệu
			if (!$data) {
				echo json_encode(["status" => "error", "message" => "Không nhận được dữ liệu"], JSON_UNESCAPED_UNICODE);
				return;
			}
			$this->session->set_userdata('formData', $data);

			date_default_timezone_set('Asia/Ho_Chi_Minh');
			$vnp_TmnCode = $this->config->item('vnp_TmnCode');
			$vnp_HashSecret = $this->config->item('vnp_HashSecret');
			$vnp_Url = $this->config->item('vnp_Url');
			$vnp_Locale = $this->config->item('vnp_Locale');
			$vnp_ReturnUrl = $this->config->item('vnp_Returnurl');
			$vnp_StartTime = date("YmdHis");
			$vnp_ExpireDate = date('YmdHis', strtotime('+15 minutes', strtotime($vnp_StartTime))); // Thời gian hết hạn
			$vnp_CurrCode = $this->config->item('vnp_CurrCode');
			$vnp_Command = $this->config->item('vnp_Command');
			$vnp_BankCode = $this->config->item('vnp_BankCode');

			// Lấy IP của user 
			$vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
			$vnp_Amount = $total_amount;

			// Kiểm tra dữ liệu nhập vào
			if (!$vnp_Amount || $vnp_Amount <= 0) {
				echo "Số tiền không hợp lệ!";
				return;
			}

			// Chuyển số tiền sang đơn vị VNĐ (VNPAY yêu cầu nhân 100)
			$vnp_Amount = $vnp_Amount * 100;

			// Mã đơn hàng duy nhất
			$vnp_TxnRef = time();
			$vnp_OrderInfo = "Thanh toán đơn hàng #" . $vnp_TxnRef;

			// Dữ liệu gửi lên VNPAY
			$inputData = array(
				"vnp_Version" => "2.1.0",
				"vnp_TmnCode" => $vnp_TmnCode,
				"vnp_Amount" => $vnp_Amount,
				"vnp_Command" => $vnp_Command,
				"vnp_CreateDate" => $vnp_StartTime,
				"vnp_CurrCode" => $vnp_CurrCode,
				"vnp_IpAddr" => $vnp_IpAddr,
				"vnp_Locale" => $vnp_Locale,
				"vnp_OrderInfo" => $vnp_OrderInfo,
				"vnp_OrderType" => "billpayment",
				"vnp_ReturnUrl" => $vnp_ReturnUrl,
				"vnp_TxnRef" => $vnp_TxnRef,
				"vnp_ExpireDate" => $vnp_ExpireDate // Thêm thời gian hết hạn vào request
			);

			// Nếu người dùng chọn ngân hàng, thêm vào request
			if (!empty($vnp_BankCode)) {
				$inputData["vnp_BankCode"] = $vnp_BankCode;
			}

			ksort($inputData);
			$query = "";
			$hashdata = "";
			$i = 0;

			foreach ($inputData as $key => $value) {
				if ($key != "vnp_SecureHash") { // Loại bỏ chữ ký cũ trước khi ký lại
					if ($i == 1) {
						$hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
					} else {
						$hashdata .= urlencode($key) . "=" . urlencode($value);
						$i = 1;
					}
					$query .= urlencode($key) . "=" . urlencode($value) . '&';
				}
			}

			// Loại bỏ ký tự '&' cuối cùng khỏi $query
			$query = rtrim($query, '&');

			$vnp_Url = $vnp_Url . "?" . $query;

			if (isset($vnp_HashSecret)) {
				$vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
				$vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;
			}
			echo json_encode(["payment_url" => $vnp_Url]);
			die();
		}
	}

	public function saveOrder()
	{
		$carts = $this->cart->contents();

		if (empty($carts)) {
			log_message('error', "Giỏ hàng trống");
			return false;
		}

		$user_id = 0;
		if ($this->session->userdata('user')) {
			$user = $this->session->userdata('user');
			$user_id = $user->id;
		}

		$total_amount = 0;
		foreach ($carts as $value) {
			$total_amount = $total_amount + $value['subtotal'];
		}

		$formData = $this->session->userdata('formData');
		// Kiểm tra tính hợp lệ của dữ liệu
		$errors = [];

		if (empty($formData['name'])) {
			$errors['name'] = "Họ và tên không được để trống";
		}

		if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = "Email không hợp lệ";
		}

		if (!preg_match('/^[0-9]{8,11}$/', $formData['phone'])) {
			$errors['phone'] = "Số điện thoại không hợp lệ (8-11 chữ số)";
		}

		if (empty($formData['address'])) {
			$errors['address'] = "Địa chỉ không được để trống";
		}

		if (empty($formData['city'])) {
			$errors['city'] = "Vui lòng chọn Tỉnh/Thành";
		}

		if (empty($formData['district'])) {
			$errors['district'] = "Vui lòng chọn Quận/Huyện";
		}

		if (empty($formData['ward'])) {
			$errors['ward'] = "Vui lòng chọn Phường/Xã";
		}

		// Nếu có lỗi, trả về danh sách lỗi
		if (!empty($errors)) {
			log_message('error', "Dữ liệu không hợp lệ");
			return false;
		}
		$data_saved = array();
		$data_saved = array(
			'user_id' => $user_id, // Nếu chưa đăng nhập, user_id = 0
			'status' => 1,
			'user_name' => $formData['name'],
			'user_email' => $formData['email'],
			'user_address' => $formData['address'],
			'user_phone' => $formData['phone'],
			'message' => $formData['message'] ?? '',
			'amount' => $total_amount,
			'payment' => $formData['payment'] ?? '',
			'created' => date('Y-m-d H:i:s')
		);

		$this->db->trans_start(); // Bắt đầu Transaction
		$this->load->model('transaction_model');
		$this->transaction_model->create($data_saved);
		$transaction_id = $this->db->insert_id();

		// Kiểm tra nếu không lấy được ID thì rollback
		if (!$transaction_id) {
			$this->db->trans_rollback();
			log_message('error', "Đặt hàng thất bại");
			return false;
		}

		$this->load->model('order_model');
		foreach ($carts as $items) {
			$data_saved = array();
			$data_saved = array(
				'transaction_id' => $transaction_id,
				'product_id' => $items['id'],
				'qty' => $items['qty'],
				'amount' => $items['subtotal']
			);
			$order_info = $this->order_model->create($data_saved);

			if (!$order_info) {
				$this->db->trans_rollback();
				log_message('error', "Đặt hàng thất bại");
				return false;
			}
		}

		$this->cart->destroy();

		// Hoàn tất transaction (tự động commit nếu không có lỗi, rollback nếu có lỗi)
		$this->db->trans_complete();

		// Trả về phản hồi
		log_message('error', "Đặt hàng thành công!");
		return true;
	}

	public function callback()
	{
		$vnp_HashSecret = $this->config->item('vnp_HashSecret');
		$inputData = array();

		foreach ($_GET as $key => $value) {
			if (substr($key, 0, 4) == "vnp_") {
				$inputData[$key] = $value;
			}
		}

		$secureHash = $inputData['vnp_SecureHash'];
		unset($inputData['vnp_SecureHash']);

		ksort($inputData);

		$i = 0;
		$hashdata = "";
		foreach ($inputData as $key => $value) {
			if ($i == 1) {
				$hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
			} else {
				$hashdata .= urlencode($key) . "=" . urlencode($value);
				$i = 1;
			}
		}

		$checkSum = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

		// Chuẩn bị dữ liệu cho view
		$this->data['txn_ref'] = $inputData['vnp_TxnRef']; // Mã đơn hàng
		$this->data['amount'] = number_format($inputData['vnp_Amount'] / 100, 2) . " VND"; // Số tiền (chia 100)
		$this->data['order_info'] = $inputData['vnp_OrderInfo']; // Nội dung thanh toán
		$this->data['response_code'] = $inputData['vnp_ResponseCode']; // Mã phản hồi
		$this->data['transaction_no'] = $inputData['vnp_TransactionNo']; // Mã GD tại VNPAY
		$this->data['bank_code'] = $inputData['vnp_BankCode']; // Mã ngân hàng
		$this->data['pay_date'] = date("d-m-Y H:i:s", strtotime($inputData['vnp_PayDate'])); // Thời gian thanh toán

		// Kiểm tra chữ ký bảo mật
		if ($checkSum === $secureHash) {
			if ($inputData['vnp_ResponseCode'] == '00') {
				if(!$this->saveOrder()){
					$this->data['error_message'] = "Lưu vào DB thất bại.";
				}
				$this->data['status'] = "Giao dịch thành công!";
			} else {
				$this->data['status'] = "Giao dịch thất bại!";
				$this->data['error_message'] = "Lỗi: " . $inputData['vnp_ResponseCode']; // Thêm thông báo lỗi
			}
		} else {
			$this->data['status'] = "Chữ ký không hợp lệ!";
			$this->data['error_message'] = "Dữ liệu phản hồi không hợp lệ!";
		}

		// Load view với dữ liệu
		$this->load->view('site/order/vnpay_return', $this->data);
	}

	public function complete()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$carts = $this->cart->contents();

			if (empty($carts)) {
				if ($this->input_is_ajax_request()) {
					// For AJAX requests
					header('Content-Type: application/json; charset=utf-8');
					http_response_code(400);
					echo json_encode(["status" => "error", "message" => "Giỏ hàng trống"], JSON_UNESCAPED_UNICODE);
					return;
				} else {
					// For form submissions
					$this->session->set_flashdata('error', 'Giỏ hàng trống');
					redirect(base_url('/'));
					return;
				}
			}

			$user_id = 0;
			if ($this->session->userdata('user')) {
				$user = $this->session->userdata('user');
				$user_id = $user->id;
			}

			$total_amount = 0;
			foreach ($carts as $value) {
				$total_amount = $total_amount + $value['subtotal'];
			}

			// Get data from either JSON or form POST
			if ($this->input_is_ajax_request()) {
				// JSON data
				$data = json_decode(file_get_contents("php://input"), true);
			} else {
				// Form POST data
				$data = $this->input->post();
			}
			
			// Kiểm tra nếu không có dữ liệu
			if (!$data) {
				if ($this->input_is_ajax_request()) {
					header('Content-Type: application/json; charset=utf-8');
					echo json_encode(["status" => "error", "message" => "Không nhận được dữ liệu"], JSON_UNESCAPED_UNICODE);
				} else {
					$this->session->set_flashdata('error', 'Không nhận được dữ liệu đơn hàng');
					redirect(base_url('order'));
				}
				return;
			}

			// Kiểm tra tính hợp lệ của dữ liệu
			$errors = [];

			if (empty($data['name'])) {
				$errors['name'] = "Họ và tên không được để trống";
			}

			if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
				$errors['email'] = "Email không hợp lệ";
			}

			if (!preg_match('/^[0-9]{8,11}$/', $data['phone'])) {
				$errors['phone'] = "Số điện thoại không hợp lệ (8-11 chữ số)";
			}

			if (empty($data['address'])) {
				$errors['address'] = "Địa chỉ không được để trống";
			}

			if (empty($data['city'])) {
				$errors['city'] = "Vui lòng chọn Tỉnh/Thành";
			}

			if (empty($data['district'])) {
				$errors['district'] = "Vui lòng chọn Quận/Huyện";
			}

			if (empty($data['ward'])) {
				$errors['ward'] = "Vui lòng chọn Phường/Xã";
			}

			// Nếu có lỗi, trả về danh sách lỗi
			if (!empty($errors)) {
				if ($this->input_is_ajax_request()) {
					// For AJAX requests
					header('Content-Type: application/json; charset=utf-8');
					http_response_code(400);
					echo json_encode(["status" => "error", "message" => "Dữ liệu không hợp lệ", "errors" => $errors],  JSON_UNESCAPED_UNICODE);
				} else {
					// For form submissions
					$this->session->set_flashdata('errors', $errors);
					redirect(base_url('order'));
				}
				return;
			}

			$data_saved = array();
			$data_saved = array(
				'user_id' => $user_id, // Nếu chưa đăng nhập, user_id = 0
				'user_name' => $data['name'],
				'user_email' => $data['email'],
				'user_address' => $data['address'],
				'user_phone' => $data['phone'],
				'message' => $data['message'] ?? '',
				'amount' => $total_amount,
				'payment' => $data['payment'] ?? '',
				'created' => date('Y-m-d H:i:s')
			);

			$this->db->trans_start(); // Bắt đầu Transaction
			$this->load->model('transaction_model');
			$this->transaction_model->create($data_saved);
			$transaction_id = $this->db->insert_id();

			// Kiểm tra nếu không lấy được ID thì rollback
			if (!$transaction_id) {
				$this->db->trans_rollback();
				if ($this->input_is_ajax_request()) {
					// For AJAX requests
					header('Content-Type: application/json; charset=utf-8');
					http_response_code(500);
					echo json_encode(["status" => "error", "message" => "Đặt hàng thất bại", "errors" => "Rollback transaction"],  JSON_UNESCAPED_UNICODE);
				} else {
					// For form submissions
					$this->session->set_flashdata('error', 'Đặt hàng thất bại. Vui lòng thử lại sau.');
					redirect(base_url('order'));
				}
				return;
			}

			$this->load->model('order_model');
			foreach ($carts as $items) {
				$data_saved = array();
				$data_saved = array(
					'transaction_id' => $transaction_id,
					'product_id' => $items['id'],
					'qty' => $items['qty'],
					'amount' => $items['subtotal']
				);
				$order_info = $this->order_model->create($data_saved);

				if (!$order_info) {
					$this->db->trans_rollback();
					if ($this->input_is_ajax_request()) {
						// For AJAX requests
						header('Content-Type: application/json; charset=utf-8');
						http_response_code(500);
						echo json_encode(["status" => "error", "message" => "Đặt hàng thất bại", "errors" => "Rollback transaction"],  JSON_UNESCAPED_UNICODE);
					} else {
						// For form submissions
						$this->session->set_flashdata('error', 'Đặt hàng thất bại. Vui lòng thử lại sau.');
						redirect(base_url('order'));
					}
					return;
				}
			}

			$this->cart->destroy();

			// Hoàn tất transaction (tự động commit nếu không có lỗi, rollback nếu có lỗi)
			$this->db->trans_complete();

			// Create success message based on payment type
			$message = "Cảm ơn quý khách đã mua hàng tại NgocLanShop";
			if($data['payment'] == 'cash'){
				$message = "Đơn vị giao hàng sẽ giao tới nhà bạn trong thời gian tới";
			} elseif($data['payment'] == 'vietqr'){
				$message = "Chúng tôi sẽ kiểm tra thông tin chuyển khoản và chuyển tới nhà bạn trong thời gian tới";
			} elseif($data['payment'] == 'pos') {
				$message = "Chúng tôi sẽ mang máy pos và hàng tới nhà bạn trong thời gian tới";
			}
			
			// Respond appropriately based on request type
			if ($this->input_is_ajax_request()) {
				// For AJAX requests
				header('Content-Type: application/json; charset=utf-8');
				echo json_encode([
					"status" => "success",
					"message" => $message,
				], JSON_UNESCAPED_UNICODE);
			} else {
				// For form submissions
				$this->session->set_flashdata('success', $message);
				redirect(base_url('/'));
			}
		}
	}

	public function sepay(){

		redirect(base_url('/'));
	}

	// Handle OPTIONS requests for CORS compatibility
	public function options()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
		header('Access-Control-Allow-Headers: Content-Type');
		header('Access-Control-Max-Age: 1728000');
		header('Content-Length: 0');
		header('Content-Type: text/plain');
		exit(0);
	}
}
