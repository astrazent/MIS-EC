<?php
require_once 'vendor/autoload.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$client = new Google_Client();
$client->setClientId(getenv('CLIENT_ID'));
$client->setClientSecret(getenv('CLIENT_SECRET'));
$client->setRedirectUri('http://localhost:8080/');
$client->addScope("email");
$client->addScope("profile");
$client->setPrompt('select_account consent'); // <- Dòng này quan trọng để buộc hiện lại yêu cầu đăng nhập

// Tạo URL để người dùng đăng nhập Google
$login_url = $client->createAuthUrl();

echo "<a href='$login_url'>Login with Google</a>";