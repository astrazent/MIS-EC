<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Verify_library
{
    protected $CI;

    public function __construct()
    {
        // &get_instance() trả về một đối tượng của CI_controller
        $this->CI = &get_instance();
        $this->CI->load->model('user_model');
        $this->CI->load->library('Jwt_library');
    }

    public function generate_verification_token($info)
    {
        $token = $this->CI->jwt_library->encode($info);
        return $token;
    }
}
