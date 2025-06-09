<!DOCTYPE html>
<html lang="en">

<head>
	<!-- ÁP DỤNG <head> TỪ FILE 1: Sử dụng Bootstrap 5 và Font Awesome -->
	<?php $this->load->view('site/head', $this->data); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <!-- Bỏ file validation.css vì đã dùng Bootstrap -->
</head>

<body>
	<!-- ÁP DỤNG CẤU TRÚC BODY TỪ FILE 1 -->

	<!-- Header -->
	<?php $this->load->view('site/header', $this->data); ?>

	<!-- Main Content -->
	<div class="container py-5">
		<!-- Breadcrumb (Tùy chọn, có thể thêm cho nhất quán) -->
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><i class="fas fa-home"></i> Trang chủ</a></li>
				<li class="breadcrumb-item active" aria-current="page">Xác nhận đổi mật khẩu</li>
			</ol>
		</nav>

		<!-- Verification/Result Section -->
		<div class="row justify-content-center">
			<div class="col-md-8 col-lg-6">
				
				<!-- GIỮ NGUYÊN HOÀN TOÀN LOGIC PHP -->
				<?php if ($this->data['status']): ?>
					<!-- Giao diện THÀNH CÔNG -->
					<div class="card text-center shadow-sm">
						<div class="card-body p-5">
							<i class="fas fa-check-circle text-success fa-5x mb-4"></i>
							<h3 class="card-title mb-3">Đổi mật khẩu thành công!</h3>
							<p class="lead mb-4">Vui lòng đăng nhập lại để có thể bắt đầu mua sắm.</p>
							<a href="<?php echo base_url('dang-nhap'); ?>" class="btn btn-primary btn-lg">
								<i class="fas fa-sign-in-alt me-2"></i>Đăng nhập ngay
							</a>
						</div>
					</div>

				<?php else: ?>
					<!-- Giao diện THẤT BẠI -->
					<div class="card text-center shadow-sm">
						<div class="card-body p-5">
							<i class="fas fa-times-circle text-danger fa-5x mb-4"></i>
							<h3 class="card-title mb-3">Đổi mật khẩu thất bại!</h3>
							
							<!-- Khối logic hiển thị thông báo lỗi chi tiết được giữ nguyên -->
							<?php if (isset($this->data['message'])): ?>
								<div class="alert alert-warning mt-3">
									<?php if ($this->data['message'] === 'invalid_token'): ?>
										<p class="mb-0">Token không hợp lệ. Vui lòng kiểm tra lại liên kết của bạn.</p>
									<?php elseif ($this->data['message'] === 'expired_token'): ?>
										<p class="mb-0">Token đã hết hạn. Vui lòng yêu cầu một liên kết mới.</p>
									<?php elseif ($this->data['message'] === 'already_use'): ?>
										<p class="mb-0">Yêu cầu này đã được sử dụng trước đó.</p>
									<?php elseif ($this->data['message'] === 'update_fail'): ?>
										<p class="mb-0">Có lỗi trong quá trình xử lý, vui lòng thử lại sau.</p>
									<?php endif; ?>
								</div>
							<?php endif; ?>

							<a href="<?php echo base_url(); ?>" class="btn btn-secondary btn-lg mt-4">
								<i class="fas fa-home me-2"></i>Về trang chủ
							</a>
						</div>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</div>

	<!-- Footer -->
	<?php $this->load->view('site/footer', $this->data); ?>

	<!-- Back to Top Button -->
	<a href="#" class="back-to-top">
		<i class="fas fa-chevron-up"></i>
	</a>

	<!-- SCRIPTS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<script src="<?php echo base_url(); ?>public/site/js/modern-script.js"></script>
	<!-- Bỏ script bootstrap cũ -->
</body>

</html>