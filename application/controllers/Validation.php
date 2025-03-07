<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Validation extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->library('Jwt_library');
    }
    public function verify_token($token) {
        try {
            // Giải mã token
            $payload = $this->jwt_library->decode($token);

            // Kiểm tra xem token có hết hạn không
            if (time() > $payload['exp']) {
                return ['status' => false, 'message' => 'expired_token'];
            }

            // Lấy thông tin user từ database
            $user_id = $payload['data']['id']['id'];
            $info = $this->user_model->get_info($user_id);

            if (!$info) {
                return ['status' => false, 'message' => 'unknown_user'];
            }

            // So sánh dữ liệu với payload
            if ($info->name !== $payload['data']['name'] || $info->email !== $payload['data']['email']) {
                return ['status' => false, 'message' => 'invalid_token'];
            }

            return [
                'status' => true,
                'message' => 'verified_token',
                'id' => $payload['data']['id']['id']
            ];
        } catch (Exception $e) {
            return ['status' => false, 'message' => 'Lỗi: ' . $e->getMessage()];
        }
    }
    public function index($token = NULL)
    {
        // Nếu không có token, trả về 404
        if ($token === NULL) {
            show_404();
            return;
        }

        $result = $this->verify_token($token);

        // Nếu xác thực thành công, cập nhật is_verified là true
        if ($result['status']) {
            $id = $result['id'];
            $data = array('is_verified' => 1);

            $this->user_model->update($id, $data);
        } // Nếu token hết hạn, xoá user khỏi DB
        elseif ($result['message'] == 'expired_token'){
            $id = $result['id'];
            $this->user_model->delete($id);
        }
        $this->data['token_status'] = $result;
        $this->load->view('site/validation/index.php', $this->data);
    }
}
