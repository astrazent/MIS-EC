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
		$test = true;

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
					'created' => date('Y-m-d H:i:s'),
					'is_verified' => 0
				);
				//Bắt đầu một transaction
				$this->db->trans_begin();
				if ($this->user_model->create($data)) {
					$user_id = $this->user_model->get_info_rule(['email' => $email], 'id');
					if (!$user_id) {
						$this->db->trans_rollback();
						$this->session->set_flashdata('message_fail', 'Không thể lấy user_id.');
						redirect(base_url('user/register'));
					}
					$info = array("id" => $user_id, "name" => $name, "email" => $email);
					$token = $this->verify_library->generate_verification_token($info);
					if (!$token) {
						$this->db->trans_rollback();
						$this->session->set_flashdata('message_fail', 'Không thể tạo token xác thực.');
						redirect(base_url('user/register'));
					}
					$verification_link = base_url("xac-thuc-mail/$token");
					$validation_email = $this->validate_email(
						$email,
						$name,
						'Xác thực Email - Quần Áo Ngọc Lan',
						"
						<!DOCTYPE html>
						<html>
						<head>
							<meta charset='UTF-8'>
							<title>Xác thực Email - Quần Áo Ngọc Lan</title>
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
							<p>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.</p>
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
						$this->db->trans_rollback();
						$this->session->set_flashdata('message_fail', 'Không thể gửi email.');
						redirect(base_url('user/register'));
					}
					//Kết thúc phiên transaction
					$this->db->trans_commit();
					$this->session->set_flashdata('message_success', "Vui lòng xác nhận email tại $email");
				} else {
					$this->session->set_flashdata('message_fail', 'Thêm người dùng thất bại!');
				}
				redirect(base_url('user/register'));
			}
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
			$this->session->unset_userdata('user');
		}
		redirect(base_url());
	}
}
