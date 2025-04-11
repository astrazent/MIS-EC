<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->library('form_validation');
		$this->load->library('verify_library');
		$this->load->helper('form');
		$this->load->helper('email');
	}

	public function index()
	{
		$user = $this->session->userdata('user');
		if (!isset($user)) {
			redirect(base_url());
		}
		
		$this->data['temp'] = 'site/user/index.php';
		$this->load->view('site/layoutsub', $this->data);
	}
	public function validate_email($to_mail, $to_name, $subject, $body, $altBody)
	{
		if (send_email($to_mail, $to_name, $subject, $body, $altBody)) {
			return true;
		} else {
			return false;
		}
	}
	public function register()
	{
		// nếu user tồn tại thì chuyển về home
		$user = $this->session->userdata('user');
		if (isset($user)) {
			redirect(base_url());
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			header('Content-Type: application/json;');
			// Nhận dữ liệu JSON từ request
			$data = json_decode(file_get_contents("php://input"), true);
			// Kiểm tra nếu không có dữ liệu
			if (!$data) {
				echo json_encode(["status" => "error", "message" => "Không nhận được dữ liệu"], JSON_UNESCAPED_UNICODE);
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
			if (!$this->check_email($data['email'])) {
				$errors['email'] = "Email đã tồn tại";
			}
			if ($data['password'] < 8) {
				echo "Mật khẩu phải từ 8 kí tự trở lên";
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

			// Kiểm tra recaptcha
			$captcha = $data['recaptcha'];
			log_message('error', $data['recaptcha']);
			if (!$this->verify_library->verify_recaptcha($captcha)) {
				$errors['recaptcha'] = "xác thực recaptcha thất bại";
			}

			// Nếu có lỗi, trả về danh sách lỗi
			if (!empty($errors)) {
				echo json_encode(["status" => "error", "message" => "Dữ liệu không hợp lệ", "errors" => $errors],  JSON_UNESCAPED_UNICODE);
				return;
			}

			$data_saved = array();
			$time = date('Y-m-d H:i:s');
			$name = $data['name'];
			$email = $data['email'];
			$data_saved = array(
				'name' => $name,
				'email' => $email,
				'password' => md5($data['password']),
				'address' => $data['address'],
				'city' => $data['city'],
				'district' => $data['district'],
				'ward' => $data['ward'],
				'phone' => $data['ward'],
				'created' => $time
			);

			// Gửi Email xác thực 
			$token = $this->verify_library->generate_verification_token($data_saved);
			if (!$token) {
				echo json_encode(["status" => "error", "message" => "Không thể tạo token xác thực", "errors" => $errors],  JSON_UNESCAPED_UNICODE);
				return;
			}
			$verification_link = base_url("xac-thuc-mail/$token");
			$validation_email = $this->validate_email(
				$email,
				$name,
				'Xác thực Email - Quần áo Ngọc Lan',
				"
				<!DOCTYPE html>
				<html>
				<head>
					<meta charset='UTF-8'>
					<title>Xác thực Email - Quần Áo Ngọc Lan</title>
					<style>
						p {
							font-size: 20px;
						}
					</style>
				</head>
				<body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; text-align: center;'>
					<h2>Chào bạn, $name </h2>
					<p>Bạn đã đăng ký tài khoản trên <strong>Quần Áo Ngọc Lan</strong>. Vui lòng xác thực email của bạn bằng cách nhấn vào nút bên dưới:</p>
					<p>
						<a href='" . $verification_link . "' 
						style='display: inline-block; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;'>
							Xác Thực Email
						</a>
					</p>
					<p>Nếu bạn không thực hiện yêu cầu trên, vui lòng bỏ qua email này.</p>
					<p>Trân trọng,<br>Đội ngũ Quần Áo Ngọc Lan</p>
				</body>
				</html>
				",
				"
				Chào bạn,

				Bạn đã đăng ký tài khoản trên Quần Áo Ngọc Lan. Vui lòng xác thực email của bạn bằng cách nhấp vào liên kết bên dưới:

				👉 " . $verification_link . "

				Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.

				Trân trọng,
				Đội ngũ Quần Áo Ngọc Lan
				"
			);
			if (!$validation_email) {
				echo json_encode(["status" => "error", "message" => "Gửi email thất bại", "errors" => $errors],  JSON_UNESCAPED_UNICODE);
				return;
			}
			echo json_encode(["status" => "success", "message" => "Vui lòng xác nhận email tại $email"],  JSON_UNESCAPED_UNICODE);
			return;
		}
		$this->load->view('site/user/register');
	}
	function check_validation_mail()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			header('Content-Type: application/json;');

			// Nhận dữ liệu JSON từ request
			$data = json_decode(file_get_contents("php://input"), true);
			$where = array('email' => $data['email']);
			if ($this->user_model->check_exists($where)) {
				echo json_encode(["status" => "success", "message" => "Xác nhận thành công"],  JSON_UNESCAPED_UNICODE);
				return;
			}
			echo json_encode(["status" => "error", "message" => "Xác nhận thất bại"],  JSON_UNESCAPED_UNICODE);
			return;
		}
	}
	function check_forgot_password_mail()
	{
		$user = $this->session->userdata('user');
		if (!isset($user)) {
			echo json_encode(["status" => "error", "message" => "Người dùng không tồn tại!"],  JSON_UNESCAPED_UNICODE);
			return;
		}

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			header('Content-Type: application/json;');

			//Kiểm tra xem email đã được kích hoạt chưa
			$verified = $this->user_model->get_info_rule(['id' => $user->id], 'is_verified');
			if ($verified->is_verified == '1') {
				echo json_encode(["status" => "error", "message" => "Email chưa được xác thực"],  JSON_UNESCAPED_UNICODE);
				return;
			}

			// Nhận dữ liệu JSON từ request
			$data = json_decode(file_get_contents("php://input"), true);

			// Lấy password ứng với user hiện tại
			$user_info = $this->user_model->get_info_rule(['id' => $user->id], 'password');
			$password = md5($user_info->password);

			// Kiểm tra xem mật khẩu đã được cập nhật chưa 
			if (md5($data['password']) === $password) {
				$temp = array(
					'is_verified' => 1
				);
				if (!$this->user_model->update($user->id, $temp)) {
					echo json_encode(["status" => "error", "message" => "cập nhật is_verified thất bại"],  JSON_UNESCAPED_UNICODE);
					return;
				}
				echo json_encode(["status" => "success", "message" => "Xác nhận thành công"],  JSON_UNESCAPED_UNICODE);
				return;
			}
			echo json_encode(["status" => "error", "message" => "Xác nhận thất bại"],  JSON_UNESCAPED_UNICODE);
			return;
		}
	}
	function check_email($email)
	{
		$where = array('email' => $email);
		if ($this->user_model->check_exists($where)) {
			$this->form_validation->set_message(__FUNCTION__, 'Tên đăng nhập đã tồn tại');
			return FALSE;
		}
		return TRUE;
	}
	public function login()
	{
		// nếu user tồn tại, redirect về home
		if ($this->session->userdata('user')) {
			redirect(base_url());
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			header('Content-Type: application/json;');

			// Nhận dữ liệu JSON từ request
			$data = json_decode(file_get_contents("php://input"), true);

			// Kiểm tra nếu không có dữ liệu
			if (!$data) {
				echo json_encode(["status" => "error", "message" => "Không nhận được dữ liệu"], JSON_UNESCAPED_UNICODE);
				return;
			}

			// Kiểm tra recaptcha
			$captcha = $data['recaptcha'];
			if (!$this->verify_library->verify_recaptcha($captcha)) {
				echo json_encode(["status" => "error", "message" => "xác thực recaptcha thất bại"], JSON_UNESCAPED_UNICODE);
				return;
			}

			//Kiểm tra tài khoản và mật khẩu
			$email = $data['email'];
			$password = $data['password'];

			log_message('error', $email);
			log_message('error', $password);
			$user = array();
			$where = array('email' => $email, 'password' => md5($password));
			$user = $this->user_model->get_info_rule($where);

			if (!$user) {
				echo json_encode(["status" => "error", "message" => "Tài khoản hoặc mật khẩu không đúng"], JSON_UNESCAPED_UNICODE);
				return;
			}

			$this->session->set_userdata('user', $user);


			echo json_encode(["status" => "success", "message" => "Đăng nhập thành công"], JSON_UNESCAPED_UNICODE);
			return;
		}
		$this->load->view('site/user/login');
	}
	public function logout()
	{
		if ($this->session->userdata('user')) {
			$this->session->unset_userdata('user');
		}
		redirect(base_url());
	}
	public function get_info_forgot_user($email)
	{
		$where = array('email' => $email);
		$id = $this->user_model->get_info_rule($where, 'id');

		if (isset($id) || $id) {
			return $id->id;
		} else {
			return false;
		}
	}
	public function forgotpassword()
	{
		// nếu user tồn tại thì tự động điền email
		$user = $this->session->userdata('user');
		if (isset($user)) {
			$this->data['auto_fill'] = $user->email;
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			header('Content-Type: application/json;');

			// Nhận dữ liệu JSON từ request
			$data = json_decode(file_get_contents("php://input"), true);

			// Kiểm tra nếu không có dữ liệu
			if (!$data) {
				echo json_encode(["status" => "error", "message" => "Không nhận được dữ liệu"], JSON_UNESCAPED_UNICODE);
				return;
			}

			// Kiểm tra recaptcha
			$captcha = $data['recaptcha'];
			if (!$this->verify_library->verify_recaptcha($captcha)) {
				$errors['recaptcha'] = "xác thực recaptcha thất bại";
			}

			//Kiểm tra mail có tồn tại không
			$email = $data['email'];

			if ($this->check_email($email)) {
				echo json_encode(["status" => "error", "message" => "Email không tồn tại"], JSON_UNESCAPED_UNICODE);
				return;
			}

			$data = array(
				'password' => md5($data['password']),
				'id' => $this->get_info_forgot_user($email)
			);
			// Gửi Email xác thực 
			$token = $this->verify_library->generate_verification_token($data);
			if (!$token) {
				echo json_encode(["status" => "error", "message" => "Không thể tạo token xác thực"], JSON_UNESCAPED_UNICODE);
				return;
			}
			$reset_link = base_url("doi-mat-khau/$token");
			$expire_time = getenv('JWT_EXPIRE') / 60;

			$validation_email = $this->validate_email(
				$email,
				$email,
				'Xác thực Email - Quần áo Ngọc Lan',
				'
					<!DOCTYPE html>
					<html>
					<head>
						<meta charset="UTF-8">
						<meta name="viewport" content="width=device-width, initial-scale=1">
						<title>Xác nhận đổi mật khẩu</title>
						<style>
							body {
								font-family: Arial, sans-serif;
								background-color: #f4f4f4;
								margin: 0;
								padding: 20px;
							}
							.container {
								max-width: 600px;
								background: #ffffff;
								padding: 20px;
								border-radius: 8px;
								box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
								text-align: center;
								margin: auto;
							}
							.button {
								display: inline-block;
								background: #007bff;
								color: white !important;
								padding: 12px 20px;
								text-decoration: none;
								border-radius: 5px;
								font-size: 16px;
							}
							.footer {
								margin-top: 20px;
								font-size: 12px;
								color: #666;
							}
						</style>
					</head>
					<body>

						<div class="container">
							<h2>Xác nhận đổi mật khẩu</h2>
							<p>Chúng tôi nhận được yêu cầu thay đổi mật khẩu cho tài khoản của bạn.</p>
							<p>Nếu bạn không yêu cầu thay đổi mật khẩu, hãy bỏ qua email này.</p>
							<p>Để đặt lại mật khẩu, vui lòng nhấn vào nút bên dưới:</p>
							<a href="' . $reset_link . '" class="button">Đổi mật khẩu</a>
							<p>Liên kết này sẽ hết hạn sau ' . $expire_time . ' phút.</p>
						</div>

					</body>
					</html>
					',
				"
					Tiêu đề: Xác nhận thay đổi mật khẩu

					Nội dung:

					Xin chào " . $email . ",

					Chúng tôi nhận được yêu cầu thay đổi mật khẩu cho tài khoản của bạn. Nếu bạn đã yêu cầu điều này, vui lòng nhấn vào liên kết dưới đây để đặt lại mật khẩu:

					👉 " . $reset_link . "

					Lưu ý: Liên kết này sẽ hết hạn sau " . $expire_time . " phút. Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.

					Nếu bạn gặp bất kỳ vấn đề nào, hãy liên hệ với chúng tôi qua email: support@ngoclan.com.

					Trân trọng,
					Đội ngũ hỗ trợ
					Ngoc Lan team
					"
			);

			if (!$validation_email) {
				echo json_encode(["status" => "error", "message" => "Không thể gửi email"], JSON_UNESCAPED_UNICODE);
				return;
			}
			echo json_encode(["status" => "success", "message" => "Vui lòng xác thực mail tại $email"], JSON_UNESCAPED_UNICODE);
			return;
		}
		$this->load->view('site/user/forgot', $this->data);
	}
	public function update_info()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data = json_decode(file_get_contents("php://input"), true);

			if (!isset($data['id']) || !isset($data['value'])) {
				echo json_encode(['status' => 'error', 'message' => 'Thiếu dữ liệu']);
				return;
			}

			$this->db->where('id', $data['id']);
			$this->db->update('users', ['name' => $data['value']]);

			echo json_encode(['status' => 'success', 'message' => 'Cập nhật thành công']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ']);
		}
	}
	public function delete_info()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
			$data = json_decode(file_get_contents("php://input"), true);

			if (!isset($data['id']) || !isset($data['value'])) {
				echo json_encode(['status' => 'error', 'message' => 'Thiếu dữ liệu']);
				return;
			}

			$this->db->where('id', $data['id']);
			$this->db->update('users', ['name' => $data['value']]);

			echo json_encode(['status' => 'success', 'message' => 'Cập nhật thành công']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ']);
		}
	}

	public function changepassword()
	{
		// Kiểm tra nếu user không tồn tại thì redirect về home 
		$user = $this->session->userdata('user');
		if (!isset($user)) {
			redirect(base_url());
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			header('Content-Type: application/json;');

			// Nhận dữ liệu JSON từ request
			$data = json_decode(file_get_contents("php://input"), true);

			// Kiểm tra nếu không có dữ liệu
			if (!$data) {
				echo json_encode(["status" => "error", "message" => "Không nhận được dữ liệu"], JSON_UNESCAPED_UNICODE);
				return;
			}

			// Kiểm tra recaptcha
			$captcha = $data['recaptcha'];
			if (!$this->verify_library->verify_recaptcha($captcha)) {
				$errors['recaptcha'] = "xác thực recaptcha thất bại";
			}

			$email = $user->email;

			$data = array(
				'password' => md5($data['password']),
				'id' => $this->get_info_forgot_user($email)
			);
			// Gửi Email xác thực 
			$token = $this->verify_library->generate_verification_token($data);
			if (!$token) {
				echo json_encode(["status" => "error", "message" => "Không thể tạo token xác thực"], JSON_UNESCAPED_UNICODE);
				return;
			}
			$reset_link = base_url("doi-mat-khau/$token");
			$expire_time = getenv('JWT_EXPIRE') / 60;

			$validation_email = $this->validate_email(
				$email,
				$email,
				'Xác thực Email - Quần áo Ngọc Lan',
				'
					<!DOCTYPE html>
					<html>
					<head>
						<meta charset="UTF-8">
						<meta name="viewport" content="width=device-width, initial-scale=1">
						<title>Xác nhận đổi mật khẩu</title>
						<style>
							body {
								font-family: Arial, sans-serif;
								background-color: #f4f4f4;
								margin: 0;
								padding: 20px;
							}
							.container {
								max-width: 600px;
								background: #ffffff;
								padding: 20px;
								border-radius: 8px;
								box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
								text-align: center;
								margin: auto;
							}
							.button {
								display: inline-block;
								background: #007bff;
								color: white !important;
								padding: 12px 20px;
								text-decoration: none;
								border-radius: 5px;
								font-size: 16px;
							}
							.footer {
								margin-top: 20px;
								font-size: 12px;
								color: #666;
							}
						</style>
					</head>
					<body>

						<div class="container">
							<h2>Xác nhận đổi mật khẩu</h2>
							<p>Chúng tôi nhận được yêu cầu thay đổi mật khẩu cho tài khoản của bạn.</p>
							<p>Nếu bạn không yêu cầu thay đổi mật khẩu, hãy bỏ qua email này.</p>
							<p>Để đặt lại mật khẩu, vui lòng nhấn vào nút bên dưới:</p>
							<a href="' . $reset_link . '" class="button">Đổi mật khẩu</a>
							<p>Liên kết này sẽ hết hạn sau ' . $expire_time . ' phút.</p>
						</div>

					</body>
					</html>
					',
				"
					Tiêu đề: Xác nhận thay đổi mật khẩu

					Nội dung:

					Xin chào " . $email . ",

					Chúng tôi nhận được yêu cầu thay đổi mật khẩu cho tài khoản của bạn. Nếu bạn đã yêu cầu điều này, vui lòng nhấn vào liên kết dưới đây để đặt lại mật khẩu:

					👉 " . $reset_link . "

					Lưu ý: Liên kết này sẽ hết hạn sau " . $expire_time . " phút. Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.

					Nếu bạn gặp bất kỳ vấn đề nào, hãy liên hệ với chúng tôi qua email: support@ngoclan.com.

					Trân trọng,
					Đội ngũ hỗ trợ
					Ngoc Lan team
					"
			);

			if (!$validation_email) {
				echo json_encode(["status" => "error", "message" => "Không thể gửi email"], JSON_UNESCAPED_UNICODE);
				return;
			}
			echo json_encode(["status" => "success", "message" => "Vui lòng xác thực mail tại $email"], JSON_UNESCAPED_UNICODE);
			return;
		}

		$this->load->view('site/user/alter', $this->data);
	}
	function check_password()
	{
		$password = $this->input->post('oldpassword');
		$where = array('password' => md5($password));
		if (!$this->user_model->check_exists($where)) {
			$this->form_validation->set_message(__FUNCTION__, 'Mật khẩu cũ sai');
			return FALSE;
		}
		return TRUE;
	}
}
