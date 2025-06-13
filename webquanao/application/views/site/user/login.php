<?php
// LOGIC TỪ FILE 2 - GIỮ NGUYÊN
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
$client->setPrompt('select_account consent');

// Tạo URL để người dùng đăng nhập Google
$login_url = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- THAY ĐỔI 1: Áp dụng toàn bộ <head> từ File 1 để có Bootstrap 5 và các script cần thiết -->
	<?php $this->load->view('site/head', $this->data); ?>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
	<!-- reCAPTCHA integration -->
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
	<!-- THAY ĐỔI 2: Áp dụng toàn bộ cấu trúc body của File 1 -->

	<!-- Announcement Bar -->
	<div class="announcement-bar">
		<div class="container">
			<div class="announcement-content">
				<p><?php
					$descriptions = [];

					foreach ($this->data['discount'] as $item) {
						$descriptions[] = $item->description;
					}

					$result = implode(' - ', $descriptions);

					echo $result;
					?></p>
			</div>
		</div>
	</div>

	<!-- Header -->
	<?php $this->load->view('site/header', $this->data); ?>

	<!-- Main Content -->
	<div class="container py-4">
		<!-- Breadcrumb -->
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><i class="fas fa-home"></i> Trang chủ</a></li>
				<li class="breadcrumb-item active" aria-current="page">Đăng nhập</li>
			</ol>
		</nav>

		<!-- Login Form Section -->
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card login-card">
					<div class="card-header">
						<h3 class="card-title">Đăng nhập tài khoản</h3>
					</div>
					<div class="card-body">
						<!-- Form vẫn giữ nguyên các ID để script login.js hoạt động -->
						<form id="loginForm" class="needs-validation" novalidate>
							<div class="row justify-content-center">
								<div class="col-md-8 mb-4">
									<label for="email" class="form-label">Email</label>
									<div class="input-group">
										<span class="input-group-text"><i class="fas fa-envelope"></i></span>
										<!-- ID và name giữ nguyên từ logic của File 2 -->
										<input type="email" class="form-control" id="email" name="email" placeholder="example@gmail.com" required>
									</div>
									<div class="invalid-feedback">Vui lòng nhập email hợp lệ</div>
								</div>
							</div>

							<div class="row justify-content-center">
								<div class="col-md-8 mb-4">
									<label for="password" class="form-label">Mật khẩu</label>
									<div class="input-group">
										<span class="input-group-text"><i class="fas fa-lock"></i></span>
										<!-- ID và name giữ nguyên từ logic của File 2 -->
										<input type="password" class="form-control" id="password" name="password" required>
									</div>
									<div class="invalid-feedback">Vui lòng nhập mật khẩu</div>
								</div>
							</div>

							<div class="mb-4 d-flex justify-content-center">
								<!-- reCAPTCHA giữ nguyên -->
								<div class="g-recaptcha" data-sitekey="6LcKdPUqAAAAAGv-BwfXyqkrqpTuVEUCQLGwbG6Z" data-callback="onCaptchaSuccess"></div>
							</div>

							<div class="d-grid gap-2 col-md-8 mx-auto">
								<!-- Nút Đăng nhập giữ nguyên ID -->
								<button type="button" id="submitBtn" class="btn btn-primary btn-lg">
									<i class="fas fa-sign-in-alt me-2"></i> Đăng nhập
								</button>
							</div>

							<!-- THAY ĐỔI 3: Tích hợp nút Đăng nhập Google vào giao diện mới -->
							<div class="text-center my-3">
								<small class="text-muted">HOẶC</small>
							</div>

							<div class="d-grid gap-2 col-md-8 mx-auto">
								<a href="<?php echo htmlspecialchars($login_url); ?>" class="btn btn-light border d-flex align-items-center justify-content-center">
									<img src="https://developers.google.com/identity/images/g-logo.png" style="height: 20px; margin-right: 10px;" alt="Google Logo" />
									<span>Đăng nhập bằng Google</span>
								</a>
							</div>

							<div class="text-center mt-4">
								<div class="row justify-content-center">
									<div class="col-md-8 d-flex justify-content-between">
										<p><a href="<?php echo base_url('dang-ky'); ?>" class="fw-bold">Đăng ký</a></p>
										<p><a href="<?php echo base_url('quen-mat-khau'); ?>" class="fw-bold">Quên mật khẩu?</a></p>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="hiddenData" data-expire="<?php echo getenv('JWT_EXPIRE'); ?>" style="display: none"></div>

	<!-- Footer -->
	<?php $this->load->view('site/footer', $this->data); ?>

	<!-- Back to Top Button -->
	<a href="#" class="back-to-top">
		<i class="fas fa-chevron-up"></i>
	</a>

	<!-- THAY ĐỔI 4: Sử dụng các script từ File 1 và giữ lại script logic của File 2 -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<script src="<?php echo base_url(); ?>public/site/js/modern-script.js"></script>
	<!-- Giữ lại script login.js vì nó chứa logic cho form này -->
	<script src="<?php echo public_url('site/'); ?>js/login.js"></script>
</body>

</html>