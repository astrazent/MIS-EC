<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Shipment extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('transaction_model');
        $this->load->library('pagination');
    }

    public function index()
    {
        $user = $this->session->userdata('user');
        $orders = [];

        if ($user) {
            $user_email = $user->email;

            // Cấu hình Pagination
            $config = array();
            $config['base_url'] = base_url('shipment/index');
            $config['total_rows'] = $this->db->where('transaction.delivery_email', $user_email)
                ->join('transaction', 'order.transaction_id = transaction.id')
                ->count_all_results('order');
            $config['per_page'] = 10; // Số đơn hàng trên mỗi trang
            $config['uri_segment'] = 3;
            $config['num_links'] = 2;

            // Cấu hình giao diện Pagination Bootstrap
            $config['full_tag_open'] = '<ul class="pagination">';
            $config['full_tag_close'] = '</ul>';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a>';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';

            // Load thư viện Pagination và khởi tạo
            $this->load->library('pagination');
            $this->pagination->initialize($config);

            // Lấy số trang hiện tại
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            // Truy vấn đơn hàng theo trang
            $this->db->select('
                order.id AS order_id,
                product.name AS product_name,
                product.image_link AS product_image,
                SUM(order.qty) AS total_qty,
                SUM(order.amount) AS total_price,
                transaction.payment AS payment_method,
                transaction.status,
                transaction.created,
                order.transaction_id
            ');
            $this->db->from('order');
            $this->db->join('transaction', 'order.transaction_id = transaction.id');
            $this->db->join('product', 'order.product_id = product.id');
            $this->db->where('transaction.delivery_email', $user_email);
            $this->db->group_by('order.product_id, order.transaction_id');
            $this->db->order_by('transaction.created', 'DESC');
            $this->db->limit($config['per_page'], $page);

            $orders = $this->db->get()->result();
        }
        $this->data['orders'] = $orders;
        $this->data['pagination'] = $this->pagination->create_links(); // Thêm pagination vào data
        $this->data['temp'] = 'site/shipment/index';
        $this->load->view('site/layoutsub', $this->data);
    }



    public function cancel_orders()
    {
        $order_ids = $this->input->post('order_ids');

        if (empty($order_ids)) {
            echo json_encode(['status' => 'error', 'message' => 'Không có đơn hàng nào được chọn!']);
            return;
        }

        // Lấy danh sách transaction_id từ các đơn hàng sẽ xóa
        $this->db->select('transaction_id');
        $this->db->where_in('id', $order_ids);
        $transactions = $this->db->get('order')->result_array();
        $transaction_ids = array_column($transactions, 'transaction_id');

        // Xóa đơn hàng trong bảng `order`
        $this->db->where_in('id', $order_ids);
        $this->db->delete('order');

        // Xóa transaction nếu không còn đơn hàng nào liên quan
        if (!empty($transaction_ids)) {
            foreach ($transaction_ids as $transaction_id) {
                $this->db->reset_query();
                $this->db->where('transaction_id', $transaction_id);
                $remaining_orders = $this->db->count_all_results('order');

                if ($remaining_orders == 0) {
                    $this->db->reset_query();
                    $this->db->where('id', $transaction_id);
                    $this->db->delete('transaction');
                }
            }
        }

        echo json_encode(['status' => 'success', 'message' => 'Đã hủy đơn hàng thành công!']);
    }
}
