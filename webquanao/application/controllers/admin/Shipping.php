<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shipping extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaction_model');
        $this->load->model('product_model');
        $this->load->model('order_model');
    }

    public function index() {
        // Get all orders with status = 2 (delivering)
        $input = array();
        $input['where'] = array('status' => 2);
        $input['order'] = array('created', 'desc');
        
        $delivering_orders = $this->transaction_model->get_list($input);
        $this->data['delivering_orders'] = $delivering_orders;
        
        // Load view
        $this->data['temp'] = 'admin/shipping/confirm_delivery';
        $this->load->view('admin/main', $this->data);
    }

    public function confirm_delivery($transaction_id) {
        // Get transaction info
        $transaction = $this->transaction_model->get_info($transaction_id);
        if (!$transaction) {
            $this->session->set_flashdata('message', 'Không tìm thấy đơn hàng');
            $this->session->set_flashdata('message_type', 'danger');
            redirect(admin_url('shipping'));
            return;
        }

        // Start transaction
        $this->db->trans_start();

        // Update transaction status to completed (3)
        $this->transaction_model->update($transaction_id, array(
            'status' => 3
        ));

        // If there's a shipping tracking ID, update its status
        if (!empty($transaction->shipping_info)) {
            $this->db->where('tracking_id', $transaction->shipping_info)
                     ->update('shipping_tracking', array(
                         'status' => 'delivered',
                         'updated_at' => date('Y-m-d H:i:s')
                     ));
        }

        // Update product purchase counts
        $input = array();
        $input['where'] = array('transaction_id' => $transaction_id);
        $orders = $this->order_model->get_list($input);
        
        if ($orders) {
            foreach ($orders as $order) {
                $product = $this->product_model->get_info($order->product_id);
                if ($product) {
                    $this->db->where('id', $order->product_id)
                             ->set('buyed', 'buyed + ' . (int)$order->qty, FALSE)
                             ->update('product');
                }
            }
        }

        // Complete transaction
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('message', 'Có lỗi xảy ra khi xác nhận giao hàng');
            $this->session->set_flashdata('message_type', 'danger');
        } else {
            $this->session->set_flashdata('message', 'Đã xác nhận giao hàng thành công');
            $this->session->set_flashdata('message_type', 'success');
        }

        redirect(admin_url('shipping'));
    }
} 