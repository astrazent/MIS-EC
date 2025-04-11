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
    public function verify_token($token)
    {
        try {
            // Giải mã token
            $payload = $this->jwt_library->decode($token);

            //Nếu payload rỗng (token không tồn tại!)
            if (!$payload) {
                return ['status' => false, 'message' => 'invalid_token'];
            }

            // Kiểm tra xem token có hết hạn không
            if (time() > $payload['exp']) {
                return ['status' => false, 'message' => 'expired_token'];
            }
            // Kiểm tra trùng lặp DB với payload
            $email = $payload['data']['email']; // Email cần kiểm tra

            $exists = $this->user_model->check_exists(['email' => $email]);

            //Nếu tồn tại, không cho phép đăng kí
            if ($exists) {
                return ['status' => false, 'message' => 'user_exist'];
            }
            return [
                'status' => true,
                'message' => 'verified_token',
                'data' => $payload['data']
            ];
        } catch (Exception $e) {
            return ['status' => false, 'message' => 'error: ' . $e->getMessage()];
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

        if ($result['status']) {
            $this->user_model->create($result['data']);
        }
        $this->data['token_info'] = $result;
        $this->load->view('site/validation/index.php', $this->data);
    }

    public function changepassword($token)
    {
        $payload = $this->jwt_library->decode($token);

        //Nếu payload rỗng (token không tồn tại!)
        if (!$payload && !$payload['data']['password'] && !$payload['data']['id']) {
            $this->data['status'] = false;
            $this->data['message'] = 'invalid_token';
            $this->load->view('site/validation/changepassword', $this->data);
            return;
        }

        // Kiểm tra xem token có hết hạn không
        if (time() > $payload['exp']) {
            $this->data['status'] = false;
            $this->data['message'] = 'expired_token';
            $this->load->view('site/validation/changepassword', $this->data);
            return;
        }

        $password = $payload['data']['password'];
        $id = $payload['data']['id'];

        $data = array(
            'password' => md5($password),
            'is_verified' => 0
        );

        // Lấy thông tin user với điều kiện email
        $user = $this->user_model->get_info_rule(['id' => $id], 'password');
        if (!$user) {
            // Nếu mật khẩu mới trùng mật khẩu cũ
            $this->data['status'] = false;
            $this->data['message'] = 'user_do_not_exist';
            $this->load->view('site/validation/changepassword', $this->data);
            return;
        }

        // So sánh mật khẩu hệ thống với mật khẩu đã mã hóa trong DB
        if ($user->password === md5($password)) {
            // Nếu mật khẩu mới trùng mật khẩu cũ
            $this->data['status'] = false;
            $this->data['message'] = 'password_exist';
            $this->load->view('site/validation/changepassword', $this->data);
            return;
        }

        // Cập nhật mật khẩu
        if (!$this->user_model->update($id, $data)) {
            $this->data['status'] = false;
            $this->data['message'] = 'update_fail';
            $this->load->view('site/validation/changepassword', $this->data);
            return;
        }
        $this->data['status'] = true;
        $this->data['message'] = 'update_success';
        $this->load->view('site/validation/changepassword', $this->data);
    }
}
