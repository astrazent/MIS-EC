<?php
defined('BASEPATH') or exit('No direct script access allowed');
class MY_Controller extends CI_Controller
{
	var $data = array();
	function __construct()
	{
		parent::__construct();
		$controller = $this->uri->segment(1);
		switch ($controller) {
			case 'admin':
				$this->load->helper('admin');
				$this->_checklogin();
				$login = $this->session->userdata("login");
				$this->data['login'] = $login;
				break;

			default:
				$this->load->model('catalog_model');
				$input = array();
				$input['where'] = array('parent_id' => '1');
				$input['order'] = array('sort_order', 'ASC');
				$catalog = $this->catalog_model->get_list($input);
				foreach ($catalog as $value) {
					$input = array();
					$input['where'] = array('parent_id' => $value->id);
					$input['order'] = array('sort_order', 'ASC');
					$sub = $this->catalog_model->get_list($input);
					$value->sub = $sub;
				}
				$this->data['catalog'] = $catalog;

				$user = $this->session->userdata('user');
				$this->data['user'] = $user;

				$this->load->model('cart_model');
				$current_carts_for_header = []; // Khởi tạo là mảng rỗng
				$current_total_items_for_header = 0; // Khởi tạo là 0

				if (isset($user)) {
					// Lấy mảng các OBJECT từ model
					$cart_objects_from_model = $this->cart_model->get_list(['where' => ['user_id' => $user->id]]);
					
					if (!empty($cart_objects_from_model)) {
						$formatted_cart_for_header = [];
						foreach ($cart_objects_from_model as $item_obj) { // $item_obj là object
							// Chuyển đổi thành mảng, tương tự như trong Cart.php -> index()
							// và cart_sh.php đang mong đợi
							$formatted_cart_for_header[] = [
								'id' => $item_obj->product_id, // Hoặc key mà cart_sh.php đang dùng
								'qty' => $item_obj->qty,
								'price' => $item_obj->price,
								'name' => $item_obj->name,
								'image_link' => $item_obj->image_link,
								'rowid' => $item_obj->rowid, 
								// 'subtotal' => $item_obj->price * $item_obj->qty, // Thêm nếu cart_sh.php dùng
							];
						}
						$current_carts_for_header = $formatted_cart_for_header; // Bây giờ là mảng các mảng
					}
					$current_total_items_for_header = (int) $this->cart_model->get_sum('qty', ['user_id' => $user->id]);
				}
				
				// Gán dữ liệu đã định dạng cho view
				$this->data['carts'] = $current_carts_for_header;
				$this->data['total_items'] = $current_total_items_for_header;
				
				// Bật profiler
				if (ENVIRONMENT == "development") {
					$this->output->enable_profiler(TRUE); //Log_dev_code
				}
				break;
		}
	}
	protected function _checklogin()
	{
		$controller = $this->uri->segment(2);
		$login = $this->session->userdata("login");
		if (!isset($login) && $controller != 'login') {
			redirect(admin_url('login'));
		}
		if (isset($login) && $controller == 'login') {
			redirect(admin_url('home'));
		}
	}
}