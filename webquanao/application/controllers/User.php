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
		$message_success = $this->session->flashdata('message_success');
		$this->data['message_success'] = $message_success;

		$message_fail = $this->session->flashdata('message_fail');
		$this->data['message_fail'] = $message_fail;

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert" style="padding:5px;border-bottom:0px;">', '</div>');
		if ($this->input->post()) {
			$this->form_validation->set_rules('name', 'Họ tên', 'required');
			$this->form_validation->set_rules('email', 'Email đăng nhập', 'required|valid_email|callback_check_email'); //Hàm callback gọi check_email()
			$this->form_validation->set_rules('password', 'Mật khẩu', 'required');
			$this->form_validation->set_rules('re_password', 'Mật khẩu nhập lại', 'matches[password]');
			$this->form_validation->set_rules('address', 'Địa chỉ', 'required');
			$this->form_validation->set_rules('phone', 'Điện thoại', 'required');
			if ($this->form_validation->run()) {
				$password = $this->input->post('password');
				$email = $this->input->post('email');
				$name = $this->input->post('name');
				$data = array();
				$data = array(
					'name' => $name,
					'email' => $email,
					'password' => md5($password),
					'address' => $this->input->post('address'),
					'phone' => $this->input->post('phone'),
					'created' => date('Y-m-d H:i:s')
				);
				// Gửi Email xác thực 
				$token = $this->verify_library->generate_verification_token($data);
				if (!$token) {
					$this->session->set_flashdata('message_fail', 'Không thể tạo token xác thực.');
					redirect(base_url('user/register'));
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
							<h2>Chào bạn, $name</h2>
							<p>Bạn đã đăng ký tài khoản trên <strong>Quần Áo Ngọc Lan</strong>. Vui lòng xác thực email của bạn bằng cách nhấn vào nút bên dưới:</p>
							<p>
								<a href='" . $verification_link . "' 
								style='display: inline-block; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #28a745; text-decoration: none; border-radius: 5px;'>
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
					$this->session->set_flashdata('message_fail', 'Không thể gửi email.');
					redirect(base_url('user/register'));
				}
				$this->session->set_flashdata('message_success', "Vui lòng xác nhận email tại $email");
			} else {
				$this->session->set_flashdata('message_fail', 'Thêm người dùng thất bại!');
			}
			redirect(base_url('user/register'));
		}
		$this->load->view('site/user/register', $this->data);
	}
	function check_email()
	{
		$email = $this->input->post('email');
		$where = array('email' => $email);
		if ($this->user_model->check_exists($where)) {
			$this->form_validation->set_message(__FUNCTION__, 'Tên đăng nhập đã tồn tại');
			return FALSE;
		}
		return TRUE;
	}
	public function login()
	{
		$this->form_validation->set_error_delimiters('<p class="text-center" style="padding:5px;border-bottom:0px;">', '</p>');
		$user = $this->session->userdata('user');
		if (isset($user)) {
			redirect(base_url());
		}
		$message_success = $this->session->flashdata('message_success');
		$this->data['message_success'] = $message_success;

		$message_fail = $this->session->flashdata('message_fail');
		$this->data['message_fail'] = $message_fail;
		if ($this->input->post()) {
			$this->form_validation->set_rules('email', 'Email đăng nhập', 'required|valid_email');
			$this->form_validation->set_rules('password', 'Mật khẩu', 'required');
			$this->form_validation->set_rules('login', 'login', 'callback_check_login');
			if ($this->form_validation->run()) {
				$user = $this->get_info_user();
				$this->session->set_userdata('user', $user);
				
				// Load cart library and cart model
				$this->load->model('cart_model');
				$this->load->library('cart');
				
				// First, destroy the current session cart completely (could be from another user)
				$this->cart->destroy();
				
				// Get database cart for this specific user
				$db_cart_items = $this->cart_model->get_items_by_user($user->id);
				
				// Load cart items from database
				if (!empty($db_cart_items)) {
                    foreach ($db_cart_items as $item) {
                        $data = array(
                            'id' => $item->product_id,
                            'qty' => $item->qty,
                            'price' => $item->price,
                            'name' => $item->name,
                            'rowid' => $item->rowid
                        );
                        
                        if (!empty($item->options)) {
                            $data['options'] = unserialize($item->options);
                        }
                        
                        if (!empty($item->image_link)) {
                            $data['image_link'] = $item->image_link;
                        }
                        
                        $this->cart->insert($data);
                    }
                }
				
				redirect(base_url('user/login'));
			}
		}
		$this->load->view('site/user/login', $this->data);
	}
	public function check_login()
	{
		$user = $this->get_info_user();
		if ($user) {
			return true;
		}
		$this->form_validation->set_message(__FUNCTION__, 'Sai email hoặc mật khẩu');
		return false;
	}
	public function get_info_user()
	{
		$user = array();
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$where = array('email' => $email, 'password' => md5($password));
		$user = $this->user_model->get_info_rule($where);
		return $user;
	}
	public function logout()
	{
		if ($this->session->userdata('user')) {
			// Before logout, ensure cart items are saved in the database
			$user = $this->session->userdata('user');
			$this->load->model('cart_model');
			$this->load->library('cart');
			
			// Save session cart to database before logout
			$session_cart = $this->cart->contents();
			if (!empty($session_cart)) {
			    foreach ($session_cart as $rowid => $item) {
			        $this->cart_model->save_item($user->id, $item);
			    }
			}
			
			// Now unset the user data
			$this->session->unset_userdata('user');
			
			// Clear the cart completely
			$this->cart->destroy();
		}
		redirect(base_url());
	}
	public function get_info_forgot_user()
	{
		$email = $this->input->post('email');
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
		$message_success = $this->session->flashdata('message_success');
		$this->data['message_success'] = $message_success;

		$message_fail = $this->session->flashdata('message_fail');
		$this->data['message_fail'] = $message_fail;

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert" style="padding:5px;border-bottom:0px;">', '</div>');

		if ($this->input->post()) {
			$this->form_validation->set_rules('email', 'Email đăng nhập', 'required|valid_email');
			$this->form_validation->set_rules('password', 'Mật khẩu mới', 'required');
			$this->form_validation->set_rules('repassword', 'Nhập lại mật khẩu', 'required|matches[password]');
			if ($this->form_validation->run()) {
				$id = $this->get_info_forgot_user();
				if (!$id) {
					$this->session->set_flashdata('message_fail', "Tài khoản email không tồn tại!");
					redirect(base_url('user/forgotpassword'));
				}
				$email = $this->input->post('email');

				$password = $this->input->post('password');
				$data = array(
					'password' => md5($password),
					'id' => $id
				);
				// Gửi Email xác thực 
				$token = $this->verify_library->generate_verification_token($data);
				if (!$token) {
					$this->session->set_flashdata('message_fail', 'Không thể tạo token xác thực.');
					redirect(base_url('user/register'));
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
							<p>Liên kết này sẽ hết hạn sau '. $expire_time .' phút.</p>
						</div>

					</body>
					</html>
					',
					"
					Tiêu đề: Xác nhận thay đổi mật khẩu

					Nội dung:

					Xin chào ". $email .",

					Chúng tôi nhận được yêu cầu thay đổi mật khẩu cho tài khoản của bạn. Nếu bạn đã yêu cầu điều này, vui lòng nhấn vào liên kết dưới đây để đặt lại mật khẩu:

					👉 ". $reset_link ."

					Lưu ý: Liên kết này sẽ hết hạn sau ". $expire_time ." phút. Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.

					Nếu bạn gặp bất kỳ vấn đề nào, hãy liên hệ với chúng tôi qua email: support@ngoclan.com.

					Trân trọng,
					Đội ngũ hỗ trợ
					Ngoc Lan team
					"
				);

				if (!$validation_email) {
					$this->session->set_flashdata('message_fail', 'Không thể gửi email.');
					redirect(base_url('user/forgotpassword'));
				}

				$this->session->set_flashdata('message_success', "Vui lòng xác thực mail tại $email");
				redirect(base_url('user/forgotpassword'));
			}
		}
		$this->load->view('site/user/forgot', $this->data);
	}

	public function changepassword()
	{
		// Kiểm tra nếu user không tồn tại thì redirect về home 
		$user = $this->session->userdata('user');
		if (!isset($user)) {
			redirect(base_url());
		}

		$message_success = $this->session->flashdata('message_success');
		$this->data['message_success'] = $message_success;

		$message_fail = $this->session->flashdata('message_fail');
		$this->data['message_fail'] = $message_fail;

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert" style="padding:5px;border-bottom:0px;">', '</div>');

		if ($this->input->post()) {
			$this->form_validation->set_rules('oldpassword', 'Mật khẩu cũ', 'required|callback_check_password');
			$this->form_validation->set_rules('password', 'Mật khẩu mới', 'required');
			$this->form_validation->set_rules('repassword', 'Nhập lại mật khẩu mới', 'required|matches[password]');
			if ($this->form_validation->run()) {

				// Lấy id của user
				$email = $user->email;
				$where = array('email' => $email);
				$id = $this->user_model->get_info_rule($where, 'id');

				//Lấy mật khẩu mới
				$password = $this->input->post('password');

				$data = array(
					'password' => $password,
					'id' => $id->id
				);
				// Kiểm tra recaptcha
					$captcha = $this->input->post('g-recaptcha-response');
					if(!$this->verify_library->verify_recaptcha($captcha)){
						$this->session->set_flashdata('message_fail', 'Xác thực recaptcha thất bại');
						redirect(base_url('user/changepassword'));
					}

				// Gửi Email xác thực 
				$token = $this->verify_library->generate_verification_token($data);
				if (!$token) {
					$this->session->set_flashdata('message_fail', 'Không thể tạo token xác thực.');
					redirect(base_url('user/changepassword'));
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
							<p>Liên kết này sẽ hết hạn sau '. $expire_time .' phút.</p>
						</div>

					</body>
					</html>
					',
					"
					Tiêu đề: Xác nhận thay đổi mật khẩu

					Nội dung:

					Xin chào ". $email .",

					Chúng tôi nhận được yêu cầu thay đổi mật khẩu cho tài khoản của bạn. Nếu bạn đã yêu cầu điều này, vui lòng nhấn vào liên kết dưới đây để đặt lại mật khẩu:

					👉 ". $reset_link ."

					Lưu ý: Liên kết này sẽ hết hạn sau ". $expire_time ." phút. Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.

					Nếu bạn gặp bất kỳ vấn đề nào, hãy liên hệ với chúng tôi qua email: support@ngoclan.com.

					Trân trọng,
					Đội ngũ hỗ trợ
					Ngoc Lan team
					"
				);
				if (!$validation_email) {
					$this->session->set_flashdata('message_fail', 'Không thể gửi email.');
					redirect(base_url('user/changepassword'));
				}
				$this->session->set_flashdata('message_success', "Vui lòng xác thực mail tại $email");
				redirect(base_url('user/changepassword'));
			}
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
