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

        $data = array(
            'password' => md5($password)
        );

        if (!$this->user_model->update($payload['data']['id'], $data)) {
            $this->data['status'] = false;
            $this->data['message'] = 'update_fail';
            $this->load->view('site/validation/changepassword', $this->data);
            return;
        }
        $this->data['status'] = true;
        $this->data['message'] = 'update_success';
        $this->load->view('site/validation/changepassword', $this->data);
    }
    public function verify_recaptcha()
    {
        // Storing google recaptcha response
        // in $recaptcha variable
        $recaptcha = $_POST['g-recaptcha-response'];

        echo $recaptcha;

        // Put secret key here, which we get
        // from google console
        $secret_key = getenv('PRIVATE_KEY');

        // Hitting request to the URL, Google will
        // respond with success or error scenario
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret='
            . $secret_key . '&response=' . $recaptcha;

        // Making request to verify captcha
        $response = file_get_contents($url);

        // Response return by google is in
        // JSON format, so we have to parse
        // that json
        $response = json_decode($response);

        // Checking, if response is true or not
        if ($response->success == true) {
            echo '<script>alert("Google reCAPTACHA verified")</script>';
        } else {
            echo '<script>alert("Error in Google reCAPTACHA")</script>';
        }
    }
}
