<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'third_party/php-jwt/JWT.php';
require_once APPPATH . 'third_party/php-jwt/TokenDecoded.php';
require_once APPPATH . 'third_party/php-jwt/TokenEncoded.php';

use Nowakowskir\JWT\JWT;
use Nowakowskir\JWT\TokenDecoded;
use Nowakowskir\JWT\TokenEncoded;

class Jwt_library {
    public function __construct()
    {
        // &get_instance() trả về một đối tượng của CI_controller
        $this->CI = &get_instance();
        
    }
    private $privateKey; // Thay bằng key của bạn
    private $algorithm = JWT::ALGORITHM_HS256; // Thay đổi thuật toán nếu cần

    public function encode($data) {
        $issuedAt = time();
        $expire = getenv('JWT_EXPIRE');
        $payload = [
            'iat' => $issuedAt,
            'exp' => $issuedAt + $expire,
            'data' => $data
        ];

        // Tạo token từ payload
        $tokenDecoded = new TokenDecoded($payload);
        $this->privateKey = getenv('JWT_PRIVATE_KEY'); // Thiết lập privateKey
        $tokenEncoded = $tokenDecoded->encode($this->privateKey, $this->algorithm);

        return $tokenEncoded->toString(); // Trả về chuỗi JWT
    }

    public function decode($token) {
        try {
            // Tạo đối tượng từ JWT
            $tokenEncoded = new TokenEncoded($token);
            
            // Giải mã token
            $tokenDecoded = $tokenEncoded->decode($this->privateKey, $this->algorithm);
            
            return $tokenDecoded->getPayload(); // Trả về payload
        } catch (Exception $e) {
            return null; // Xử lý lỗi nếu token không hợp lệ
        }
    }
}
