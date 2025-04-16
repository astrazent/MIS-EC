<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends MY_Controller {
	public function __construct()
	{
		parent::__construct();

        $this->load->library('cart');
        $this->load->model('product_model');
        $this->load->model('cart_model');
	}

	public function index()
	{
		// Ensure we have the correct cart for the current user state
		$this->_ensure_correct_cart();
		
		$message = $this->session->flashdata('message');
		$this->data['message'] = $message;

		$carts = $this->cart->contents();
		$this->data['carts'] = $carts;

		$total_items = $this->cart->total_items();
		$this->data['total_items'] = $total_items;
		
		$this->data['temp']='site/cart/index';
		$this->load->view('site/layoutsub',$this->data);
	}
	
	/**
	 * Save the cart to the appropriate storage.
	 * For logged-in users, save to database. For guests, keep in session only.
	 */
	private function _save_cart() {
		$user = $this->session->userdata('user');
		if ($user) {
			// User is logged in, save cart to database
			$cart_items = $this->cart->contents();
			foreach ($cart_items as $rowid => $item) {
				$this->cart_model->save_item($user->id, $item);
			}
		}
	}
	
	/**
	 * Check if the user is logged in and has the appropriate cart
	 * If a guest tries to use the cart, ensure it's empty first if another user was logged in before
	 */
	private function _ensure_correct_cart() {
		// Get current user (if any)
		$user = $this->session->userdata('user');
		
		// Check if there was a previously logged in user
		$previous_user = $this->session->userdata('previous_logged_in');
		
		// If no user is logged in now, but there was one before, clear the cart
		if (!$user && $previous_user) {
			$this->cart->destroy();
			$this->session->unset_userdata('previous_logged_in');
		}
		
		// If user is logged in, set the previous_logged_in flag
		if ($user) {
			$this->session->set_userdata('previous_logged_in', true);
		}
	}
	
	public function add()
	{
		// Ensure we have the correct cart for the current user state
		$this->_ensure_correct_cart();
		
		$id = $this->uri->rsegment(3);
		$id = intval($id);
		$product = $this->product_model->get_product_with_discount($id);
		$data = array();
		$qty = 1;
		$price = $product->price;
		if ($product->discount > 0) {
			$price = $product->price - $product->discount;
		}
		$data['id'] = $id;
		$data['qty'] = $qty;
		$data['price'] = $price;
		$data['name'] = $product->name;
		$data['image_link'] = $product->image_link;
		
		// Add to cart library (session)
		$rowid = $this->cart->insert($data);
		
		// If user is logged in, also save to database
		$user = $this->session->userdata('user');
		if ($user) {
		    $item = $this->cart->get_item($rowid);
		    if ($item) {
		        $this->cart_model->save_item($user->id, $item);
		    }
		}
		
		redirect(base_url('cart'));
	}
	
	public function update()
	{
		// Ensure we have the correct cart for the current user state
		$this->_ensure_correct_cart();
		
		$carts = $this->cart->contents();
		$id = $this->uri->segment(3);
		$str = $this->uri->segment(4);
		foreach ($carts as $key => $value) {
			if ($value['id'] == $id ) {
				if ($str == 'sum') {
					$data=array();
					$data['rowid'] = $key;
					$data['qty'] = $value['qty'] + 1;
					$this->cart->update($data);
					
					// Update in database if user is logged in
					$user = $this->session->userdata('user');
					if ($user) {
					    $this->cart_model->update_qty($user->id, $key, $data['qty']);
					}
				} elseif($str == 'sub') {
					$data=array();
					$data['rowid'] = $key;
					$data['qty'] = $value['qty'] - 1;
					$this->cart->update($data);
					
					// Update in database if user is logged in
					$user = $this->session->userdata('user');
					if ($user) {
					    $this->cart_model->update_qty($user->id, $key, $data['qty']);
					}
				}
			}
		}
		redirect(base_url('cart'));
	}
	
	public function update_ajax()
	{
		header('Content-Type: application/json'); // Thêm header để chỉ định response type

		// Ensure we have the correct cart for the current user state
		$this->_ensure_correct_cart();
		
		$id = $this->uri->segment(3);
		$str = $this->uri->segment(4);
		$carts = $this->cart->contents();
		
		foreach ($carts as $key => $value) {
			if ($value['id'] == $id) {
				$data = array();
				$data['rowid'] = $key;
				if ($str == 'sum') {
					$data['qty'] = $value['qty'] + 1;
				} elseif($str == 'sub' && $value['qty'] > 1) {
					$data['qty'] = $value['qty'] - 1;
				}
				$this->cart->update($data);
				
				// Update in database if user is logged in
				$user = $this->session->userdata('user');
				if ($user) {
				    $this->cart_model->update_qty($user->id, $key, $data['qty']);
				}
				
				// Get updated cart info
				$updated_cart = $this->cart->contents();
				foreach ($updated_cart as $item) {
					if ($item['id'] == $id) {
						$response = array(
							'status' => 'success',
							'qty' => $item['qty'],
							'subtotal' => number_format($item['subtotal']),
							'total_price' => number_format($this->cart->total()),
							'total_items' => $this->cart->total_items()
						);
						exit(json_encode($response)); // Sử dụng exit() để đảm bảo không có output khác
					}
				}
			}
		}
		
		// Trường hợp không tìm thấy sản phẩm
		exit(json_encode(array('status' => 'error')));
	}
	
	public function del()
	{
		// Ensure we have the correct cart for the current user state
		$this->_ensure_correct_cart();
		
		$carts = $this->cart->contents();
		$id = $this->uri->segment(3);
		$id = intval($id);
		if ($id > 0) {
			foreach ($carts as $key => $value) {
				if ($value['id'] == $id) {
					$data=array();
					$data['rowid'] = $key;
					$data['qty'] = 0;
					$this->cart->update($data);
					
					// Remove from database if user is logged in
					$user = $this->session->userdata('user');
					if ($user) {
					    $this->cart_model->remove_item($user->id, $key);
					}
					
					$this->session->set_flashdata('message', 'Xóa sản phẩm thành công');
					redirect(base_url('cart'));
				}
			}
		} else {
			$this->cart->destroy();
			
			// Clear cart in database if user is logged in
			$user = $this->session->userdata('user');
			if ($user) {
			    $this->cart_model->clear_cart($user->id);
			}
			
			$this->session->set_flashdata('message', 'Xóa giỏ hàng thành công');
			redirect(base_url('cart'));
		}
	}
	
	/**
	 * Sync the cart data between session and database
	 * - Clear the session cart 
	 * - Load the user's cart from database
	 */
	public function sync_cart() {
	    $user = $this->session->userdata('user');
	    if (!$user) {
	        redirect(base_url('cart'));
	        return;
	    }
	    
	    // Clear the current session cart first
	    $this->cart->destroy();
	    
	    // Get database cart
	    $db_cart_items = $this->cart_model->get_items_by_user($user->id);
	    
	    if (!empty($db_cart_items)) {
	        // Session cart is empty but we have cart items in database
	        // Load cart from database
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
	    
	    redirect(base_url('cart'));
	}
}
