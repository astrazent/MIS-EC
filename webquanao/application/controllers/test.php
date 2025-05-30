<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';; // đường dẫn thư viện Google Client

class Test extends MY_Controller
{

	public function index()
	{
		// Xoá toàn bộ session (hoặc xoá từng key cụ thể)
		$this->session->unset_userdata('google_id');
		$this->session->unset_userdata('google_name');
		$this->session->unset_userdata('google_email');
		$this->session->unset_userdata('google_picture');

		// Hoặc nếu muốn xoá hết tất cả session luôn:
		$this->session->sess_destroy();

		// Load view
		$this->load->view('site/user/test');
	}
}
