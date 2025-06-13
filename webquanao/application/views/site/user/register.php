<?php
// PHP logic để load view và các data cần thiết (giữ nguyên từ File 2 gốc)
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
// Không cần logic Google Client ở đây vì đây là trang Đăng Ký
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<!-- ÁP DỤNG <head> TỪ FILE 1: Sử dụng Bootstrap 5 và các script cần thiết -->
	<?php $this->load->view('site/head', $this->data); ?>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
	<!-- reCAPTCHA integration -->
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- Thêm Font Awesome để hiển thị icon (tương tự File 1) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>

<body>
	<!-- ÁP DỤNG CẤU TRÚC BODY TỪ FILE 1 -->

	<!-- Announcement Bar (Tùy chọn, có thể thêm cho đồng bộ) -->
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
				<li class="breadcrumb-item active" aria-current="page">Đăng ký</li>
			</ol>
		</nav>

		<!-- Registration Form Section - Áp dụng UI của File 1 cho Form của File 2 -->
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card login-card">
					<div class="card-header">
						<h3 class="card-title">Đăng ký tài khoản</h3>
					</div>
					<div class="card-body">
						<!-- FORM GIỮ NGUYÊN LOGIC CỦA FILE 2 -->
						<form id="registerForm" class="needs-validation" novalidate>
							<!-- Họ và Tên -->
							<div class="mb-3">
								<label for="name" class="form-label">Họ và tên</label>
								<div class="input-group">
									<span class="input-group-text"><i class="fas fa-user"></i></span>
									<input type="text" class="form-control" id="name" name="name" autocomplete="new-name" required>
								</div>
								<div class="invalid-feedback">Vui lòng nhập họ và tên.</div>
							</div>
							
							<!-- Email -->
							<div class="mb-3">
								<label for="email" class="form-label">Email</label>
								<div class="input-group">
									<span class="input-group-text"><i class="fas fa-envelope"></i></span>
									<input type="email" class="form-control" id="email" name="email" autocomplete="new-email" placeholder="example@gmail.com" required>
								</div>
								<div class="invalid-feedback">Vui lòng nhập email hợp lệ.</div>
							</div>
							
							<!-- Mật khẩu -->
							<div class="mb-3">
								<label for="password" class="form-label">Mật khẩu</label>
								<div class="input-group">
									<span class="input-group-text"><i class="fas fa-lock"></i></span>
									<input type="password" class="form-control" id="password" name="password" autocomplete="new-password" required>
								</div>
								<div class="invalid-feedback">Vui lòng nhập mật khẩu.</div>
							</div>

							<!-- Nhập lại mật khẩu -->
							<div class="mb-3">
								<label for="re-password" class="form-label">Nhập lại mật khẩu</label>
								<div class="input-group">
									<span class="input-group-text"><i class="fas fa-check-circle"></i></span>
									<input type="password" class="form-control" id="re-password" name="re-password" autocomplete="new-password" required>
								</div>
								<div class="invalid-feedback">Vui lòng xác nhận mật khẩu.</div>
							</div>

							<!-- Số điện thoại -->
							<div class="mb-3">
								<label for="phone" class="form-label">Số điện thoại</label>
								<div class="input-group">
									<span class="input-group-text"><i class="fas fa-phone"></i></span>
									<input type="text" class="form-control" id="phone" name="phone" autocomplete="new-phone" required>
								</div>
								<div class="invalid-feedback">Vui lòng nhập số điện thoại.</div>
							</div>

							<!-- Địa chỉ -->
							<div class="mb-3">
								<label for="address" class="form-label">Địa chỉ</label>
								<div class="input-group">
									<span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
									<input type="text" class="form-control" id="address" name="address" autocomplete="new-address" placeholder="Ví dụ: 208-E5" required>
								</div>
								<div class="invalid-feedback">Vui lòng nhập địa chỉ.</div>
							</div>
							
							<!-- Tỉnh/Thành, Quận/Huyện, Phường/Xã -->
							<div class="row g-3 mb-3">
								<div class="col-md-4">
									<label for="city" class="form-label">Tỉnh/Thành phố</label>
									<select id="city" name="city" autocomplete="new-city" class="form-select" required>
										<option value="" selected disabled>Chọn Tỉnh/Thành</option>
									</select>
									<div class="invalid-feedback">Vui lòng chọn Tỉnh/Thành.</div>
								</div>
								<div class="col-md-4">
									<label for="district" class="form-label">Quận/Huyện</label>
									<select id="district" name="district" autocomplete="new-district" class="form-select" required>
										<option value="" selected disabled>Chọn Quận/Huyện</option>
									</select>
									<div class="invalid-feedback">Vui lòng chọn Quận/Huyện.</div>
								</div>
								<div class="col-md-4">
									<label for="ward" class="form-label">Phường/Xã</label>
									<select id="ward" name="ward" autocomplete="new-ward" class="form-select" required>
										<option value="" selected disabled>Chọn Phường/Xã</option>
									</select>
									<div class="invalid-feedback">Vui lòng chọn Phường/Xã.</div>
								</div>
							</div>

							<!-- reCAPTCHA (giữ nguyên) -->
							<div class="mb-4 d-flex justify-content-center">
								<div class="g-recaptcha" data-sitekey="6LcKdPUqAAAAAGv-BwfXyqkrqpTuVEUCQLGwbG6Z" data-callback="onCaptchaSuccess"></div>
							</div>
							
							<!-- Nút Đăng ký (giữ nguyên id="submitBtn") -->
							<div class="d-grid gap-2 col-md-8 mx-auto">
								<button type="button" id="submitBtn" class="btn btn-primary btn-lg">
									<i class="fas fa-user-plus me-2"></i> Đăng ký
								</button>
							</div>
							
							<div class="text-center mt-4">
								<p>Đã có tài khoản? <a href="<?php echo base_url('dang-nhap'); ?>" class="fw-bold">Đăng nhập ngay</a></p>
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

	<!-- ÁP DỤNG SCRIPT TỪ FILE 1 VÀ GIỮ LẠI SCRIPT LOGIC CỦA FILE 2 -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<script src="<?php echo base_url(); ?>public/site/js/modern-script.js"></script>
	<!-- GIỮ LẠI SCRIPT register.js vì nó chứa logic cho form này -->
	<script src="<?php echo public_url('site/'); ?>js/register.js"></script>
</body>

</html>