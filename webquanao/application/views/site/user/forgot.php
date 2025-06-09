<!DOCTYPE html>
<html lang="en">

<head>
	<!-- ÁP DỤNG <head> TỪ FILE 1: Sử dụng Bootstrap 5 và các script/style cần thiết -->
	<?php $this->load->view('site/head', $this->data); ?>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script> <!-- Giả sử cần axios cho forgot.js -->
	<!-- reCAPTCHA integration -->
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- Thêm Font Awesome để hiển thị icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>

<body>
	<!-- ÁP DỤNG CẤU TRÚC BODY TỪ FILE 1 -->

	<!-- Announcement Bar (Tùy chọn) -->
	<div class="announcement-bar">
		<div class="container">
			<div class="announcement-content">
				<p>Free shipping on all orders over 500.000 VNĐ</p>
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
				<li class="breadcrumb-item active" aria-current="page">Quên mật khẩu</li>
			</ol>
		</nav>

		<!-- Forgot Password Form Section -->
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card login-card">
					<div class="card-header">
						<h3 class="card-title">Đặt lại mật khẩu</h3>
					</div>
					<div class="card-body">
						<!-- FORM GIỮ NGUYÊN LOGIC -->
						<form id="forgotPasswordForm" class="needs-validation" novalidate>
							<div class="row justify-content-center">
								<!-- Email -->
								<div class="col-md-8 mb-4">
									<label for="email" class="form-label">Email</label>
									<div class="input-group">
										<span class="input-group-text"><i class="fas fa-envelope"></i></span>
										<!-- ID, name, và logic PHP cho auto_fill được giữ nguyên -->
										<input type="email" class="form-control" id="email" name="email" required
											<?php if (isset($auto_fill) && $auto_fill !== ''): ?>
												value="<?= htmlspecialchars($auto_fill, ENT_QUOTES, 'UTF-8') ?>" readonly
											<?php else: ?>
												placeholder="Nhập email đã đăng ký"
											<?php endif; ?>>
									</div>
									<div class="invalid-feedback">Vui lòng nhập email hợp lệ.</div>
								</div>
							</div>
							
							<!-- Mật khẩu mới -->
							<div class="row justify-content-center">
								<div class="col-md-8 mb-4">
									<label for="password" class="form-label">Mật khẩu mới</label>
									<div class="input-group">
										<span class="input-group-text"><i class="fas fa-lock"></i></span>
										<!-- ID và name được giữ nguyên -->
										<input type="password" class="form-control" id="password" name="password" required>
									</div>
									<div class="invalid-feedback">Vui lòng nhập mật khẩu mới.</div>
								</div>
							</div>
							
							<!-- Nhập lại mật khẩu mới -->
							<div class="row justify-content-center">
								<div class="col-md-8 mb-4">
									<label for="repassword" class="form-label">Nhập lại mật khẩu mới</label>
									<div class="input-group">
										<span class="input-group-text"><i class="fas fa-check-circle"></i></span>
										<!-- ID và name được giữ nguyên -->
										<input type="password" class="form-control" id="repassword" name="repassword" required>
									</div>
									<div class="invalid-feedback">Vui lòng xác nhận mật khẩu.</div>
								</div>
							</div>

							<!-- reCAPTCHA (giữ nguyên) -->
							<div class="mb-4 d-flex justify-content-center">
								<div class="g-recaptcha" data-sitekey="6LcKdPUqAAAAAGv-BwfXyqkrqpTuVEUCQLGwbG6Z" data-callback="onCaptchaSuccess"></div>
							</div>
							
							<!-- Nút Đổi mật khẩu (giữ nguyên id="submitBtn") -->
							<div class="d-grid gap-2 col-md-8 mx-auto">
								<button type="button" id="submitBtn" class="btn btn-primary btn-lg">
									<i class="fas fa-key me-2"></i> Đổi mật khẩu
								</button>
							</div>
							
							<!-- Các link điều hướng (giữ nguyên logic PHP) -->
							<div class="text-center mt-4">
								<?php if (!isset($auto_fill)): ?>
								<div class="row justify-content-center">
									<div class="col-md-8 d-flex justify-content-between">
										<p><a href="<?php echo base_url('dang-ky'); ?>" class="fw-bold">Đăng ký</a></p>
										<p><a href="<?php echo base_url('dang-nhap'); ?>" class="fw-bold">Đăng nhập</a></p>
									</div>
								</div>
								<?php endif; ?>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Thẻ hiddenData (giữ nguyên) -->
	<div id="hiddenData" data-expire="<?php echo getenv('JWT_EXPIRE'); ?>" style="display: none"></div>

	<!-- Footer -->
	<?php $this->load->view('site/footer', $this->data); ?>

	<!-- Back to Top Button -->
	<a href="#" class="back-to-top">
		<i class="fas fa-chevron-up"></i>
	</a>

	<!-- SCRIPT -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<script src="<?php echo base_url(); ?>public/site/js/modern-script.js"></script>
	<!-- GIỮ LẠI SCRIPT forgot.js vì nó chứa logic cho form này -->
	<script src="<?php echo public_url('site/'); ?>js/forgot.js"></script>
</body>

</html>