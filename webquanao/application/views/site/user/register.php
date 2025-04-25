<!DOCTYPE html>
<html lang="en">

<head>
	<?php $this->load->view('site/head', $this->data); ?>
	<style>
		.error-message {
			color: red;
			font-size: 12px;
			margin-top: 5px;
		}
	</style>
</head>

<body>
	<div class="container">
		<?php $this->load->view('site/header', $this->data); ?>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearpadding" style="margin-top: 15px;">
				<ol class="breadcrumb">
					<li><a href="#"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Home</a></li>
					<li class="active">Đăng kí</li>
				</ol>
				<div class="panel panel-info ">

					<?php if (isset($message_success) && !empty($message_success)) { ?>
						<h4 style="color:green;text-align: center;margin-top: 30px"><?php echo $message_success; ?></h4>

					<?php } ?>
					<?php if (isset($message_fail) && !empty($message_fail)) { ?>
						<h4 style="color:red;text-align: center;margin-top: 30px"><?php echo $message_fail; ?></h4>
					<?php } ?>
					<div class="panel-body">
						<form class="form-horizontal" method="post" action="<?php echo base_url('user/register'); ?>" id="registerForm">
							<div class="form-group">
								<label for="fullName" class="col-sm-offset-2 col-sm-2 control-label">Họ tên</label>
								<div class="col-sm-4">
									<input type="text" class="form-control" id="fullName" placeholder="" name="name" value="<?php echo set_value('name'); ?>">
									<div class="error-message" id="nameError"></div>
								</div>
								<div class="col-sm-3">
									<?php echo form_error('name'); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="userEmail" class=" col-sm-offset-2 col-sm-2 control-label">Email</label>
								<div class="col-sm-4">
									<input type="email" class="form-control" id="userEmail" placeholder="" name="email" value="<?php echo set_value('email'); ?>">
									<div class="error-message" id="emailError"></div>
								</div>
								<div class="col-sm-3">
									<?php echo form_error('email'); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="userPassword" class="col-sm-offset-2 col-sm-2 control-label">Mật khẩu</label>
								<div class="col-sm-4">
									<input type="password" class="form-control" id="userPassword" placeholder="" name="password">
									<div class="error-message" id="passwordError"></div>
								</div>
								<div class="col-sm-3">
									<?php echo form_error('password'); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="rePassword" class=" col-sm-offset-2 col-sm-2 control-label">Nhập lại mật khẩu</label>
								<div class="col-sm-4">
									<input type="password" class="form-control" id="rePassword" placeholder="" name="re_password">
									<div class="error-message" id="rePasswordError"></div>
								</div>
								<div class="col-sm-3">
									<?php echo form_error('re_password'); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="userAddress" class="col-sm-offset-2 col-sm-2 control-label">Địa chỉ</label>
								<div class="col-sm-4">
									<input type="text" class="form-control" id="userAddress" placeholder="" name="address" value="<?php echo set_value('address'); ?>">
									<div class="error-message" id="addressError"></div>
								</div>
								<div class="col-sm-3">
									<?php echo form_error('address'); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="userPhone" class="col-sm-offset-2 col-sm-2 control-label">Số điện thoại</label>
								<div class="col-sm-4">
									<input type="text" class="form-control" id="userPhone" placeholder="" name="phone" value="<?php echo set_value('phone'); ?>">
									<div class="error-message" id="phoneError"></div>
								</div>
								<div class="col-sm-3">
									<?php echo form_error('phone'); ?>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-4 col-sm-7">
									<button type="submit" class="btn btn-success">Đăng ký</button>
								</div>
							</div>
						</form>
					</div>

				</div>
			</div>
		</div>
		<?php $this->load->view('site/footer', $this->data); ?>
	</div>
	<script src="<?php echo public_url('site/'); ?>bootstrap/js/bootstrap.min.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const form = document.getElementById('registerForm');
			const fullName = document.getElementById('fullName');
			const userPassword = document.getElementById('userPassword');
			const rePassword = document.getElementById('rePassword');
			const userPhone = document.getElementById('userPhone');

			// Kiểm tra họ tên không chứa số và ký tự đặc biệt
			fullName.addEventListener('input', function() {
				const nameRegex = /^[\p{L} ]+$/u; // Chỉ cho phép chữ cái và khoảng trắng, hỗ trợ Unicode
				const nameError = document.getElementById('nameError');
				
				if (!nameRegex.test(this.value) && this.value.trim() !== '') {
					nameError.textContent = 'Họ tên không được chứa số hoặc ký tự đặc biệt';
					this.classList.add('is-invalid');
				} else {
					nameError.textContent = '';
					this.classList.remove('is-invalid');
				}
			});

			// Kiểm tra mật khẩu đủ 8 ký tự
			userPassword.addEventListener('input', function() {
				const passwordError = document.getElementById('passwordError');
				
				if (this.value.length < 8 && this.value.length > 0) {
					passwordError.textContent = 'Mật khẩu phải có ít nhất 8 ký tự';
					this.classList.add('is-invalid');
				} else {
					passwordError.textContent = '';
					this.classList.remove('is-invalid');
				}
			});

			// Kiểm tra nhập lại mật khẩu
			rePassword.addEventListener('input', function() {
				const rePasswordError = document.getElementById('rePasswordError');
				
				if (this.value !== userPassword.value && this.value.length > 0) {
					rePasswordError.textContent = 'Mật khẩu nhập lại không khớp';
					this.classList.add('is-invalid');
				} else {
					rePasswordError.textContent = '';
					this.classList.remove('is-invalid');
				}
			});

			// Kiểm tra số điện thoại đủ 10 số và bắt đầu bằng số 0
			userPhone.addEventListener('input', function() {
				const phoneRegex = /^0\d{9}$/; // Bắt đầu bằng số 0 và có tổng cộng 10 số
				const phoneError = document.getElementById('phoneError');
				
				if (!phoneRegex.test(this.value) && this.value.trim() !== '') {
					phoneError.textContent = 'Số điện thoại phải có 10 số và bắt đầu bằng số 0';
					this.classList.add('is-invalid');
				} else {
					phoneError.textContent = '';
					this.classList.remove('is-invalid');
				}
			});

			// Kiểm tra form trước khi submit
			form.addEventListener('submit', function(event) {
				let isValid = true;
				
				// Kiểm tra họ tên
				const nameRegex = /^[\p{L} ]+$/u;
				if (!nameRegex.test(fullName.value) || fullName.value.trim() === '') {
					document.getElementById('nameError').textContent = 'Họ tên không được chứa số hoặc ký tự đặc biệt và không được để trống';
					isValid = false;
				}

				// Kiểm tra mật khẩu
				if (userPassword.value.length < 8) {
					document.getElementById('passwordError').textContent = 'Mật khẩu phải có ít nhất 8 ký tự';
					isValid = false;
				}

				// Kiểm tra nhập lại mật khẩu
				if (rePassword.value !== userPassword.value) {
					document.getElementById('rePasswordError').textContent = 'Mật khẩu nhập lại không khớp';
					isValid = false;
				}

				// Kiểm tra số điện thoại
				const phoneRegex = /^0\d{9}$/;
				if (!phoneRegex.test(userPhone.value)) {
					document.getElementById('phoneError').textContent = 'Số điện thoại phải có 10 số và bắt đầu bằng số 0';
					isValid = false;
				}

				if (!isValid) {
					event.preventDefault();
				}
			});
		});
	</script>
</body>

</html>