<?php
defined('BASEPATH') or exit('No direct script access allowed'); //Dòng này giúp ngăn chặn truy cập trực tiếp  vào file Home.php mà không thông qua CodeIgniter.
require_once FCPATH . 'vendor/autoload.php'; // đường dẫn thư viện Google Client

class Home extends MY_Controller
{ // Home là một Controller kế thừa từ MY_Controller, có nghĩa là nó mở rộng các chức năng từ MY_Controller (một Controller gốc do bạn tạo ra thay vì CI_Controller).
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
	}

	public function index() //Khi người dùng truy cập trang chủ (http://yourdomain.com/), hệ thống sẽ gọi Controller mặc định được cấu hình trong routes.php:
	{
		$this->load->model('slider_model');
		$input = array();
		$input['order'] = array('sort_order', 'DESC');
		$slider = $this->slider_model->get_list($input);
		$this->data['slider'] = $slider;

		$this->load->model('product_model');
		$input = array();
		$input['order'] = array('id', 'DESC');
		$input['limit'] = array('12', '0');
		$new_product = $this->product_model->get_products_with_discount($input);
		$this->data['new_product'] = $new_product;

		$input['order'] = array('buyed', 'DESC');
		$input['limit'] = array('12', '0');
		$hot_product = $this->product_model->get_products_with_discount($input);
		$this->data['hot_product'] = $hot_product;

		$input['order'] = array('view', 'DESC');
		$input['limit'] = array('12', '0');
		$view_product = $this->product_model->get_products_with_discount($input);
		$this->data['view_product'] = $view_product;


		$client = new Google_Client();
		$client->setClientId(getenv('CLIENT_ID'));
		$client->setClientSecret(getenv('CLIENT_SECRET'));
		$client->setRedirectUri('http://localhost:8080/'); // URL redirect phải khớp với Google Console
		$client->addScope("email");
		$client->addScope("profile");

		// Lấy code Google trả về sau khi user login thành công
		$code = $this->input->get('code');

		if ($code) {
			// Lấy access token từ code
			$token = $client->fetchAccessTokenWithAuthCode($code);

			if (!isset($token['error'])) {
				$access_token = $token['access_token'];
				$client->setAccessToken($access_token);

				// Gọi API userinfo thủ công, không dùng Google_Service_Oauth2
				$url = "https://www.googleapis.com/oauth2/v3/userinfo";

				$headers = [
					"Authorization: Bearer " . $access_token
				];

				// Khởi tạo curl
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($ch);
				$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);

				if ($httpcode == 200 && $response) {
					$google_user = json_decode($response);

					// Lưu thông tin user vào session
					$this->session->set_userdata('google_id', $google_user->sub);
					$this->session->set_userdata('google_name', $google_user->name);
					$this->session->set_userdata('google_email', $google_user->email);
					$this->session->set_userdata('google_picture', $google_user->picture);

					// Redirect về index để xóa query string code khỏi URL
					redirect(base_url("/"));
					return;
				} else {
					log_message('error', 'Lỗi lấy thông tin user từ Google: HTTP ' . $httpcode . ' - Response: ' . $response);
					show_error('Lỗi lấy thông tin user từ Google.');
					return;
				}
			} else {
				log_message('error', 'Lỗi lấy access token: ' . print_r($token, true));
				show_error('Lỗi lấy access token từ Google.');
				return;
			}
		}

		// Kiểm tra nếu chưa login
		if (!$this->session->userdata('google_id')) {
			redirect(base_url("/dang-nhap"));
			return;
		}

		// Nếu đã login, lấy thông tin user từ session
		$name = $this->session->userdata('google_name');
		$email = $this->session->userdata('google_email');

		$where = array('email' => $email);
		$user = $this->user_model->get_info_rule($where);
		if (!$user) {
			$data = [
				'name' => $name,
				'email' => $email,
				'created' => date('Y-m-d H:i:s'),
				'is_verified' => 1
			];

			$inserted = $this->user_model->create($data);
			if (!$inserted) {
				redirect(base_url("/dang-nhap"));
			}
		} else {
			$this->session->set_userdata('user', $user);
		}

		$this->data['temp'] = 'site/home/index.php';
		$this->load->view('site/layout', $this->data);
	}
}
