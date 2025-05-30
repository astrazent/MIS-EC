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
$client->setPrompt('select_account consent');

// Tạo URL để người dùng đăng nhập Google
$login_url = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<?php $this->load->view('site/head', $this->data); ?>
</head>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

<!-- tích hợp reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer>
</script>
<!-- tích hợp reCAPTCHA -->

<style>
	.login-links {
		font-size: 22px;
		color: #3498db;
	}

	.login-links .link {
		text-decoration: none;
		color: #3498db;
		transition: color 0.3s, text-decoration 0.3s;
		font-weight: 500;
	}

	.login-links .link:hover {
		color: #217dbb;
		text-decoration: underline;
	}

	.g-recaptcha>div:first-child {
		margin: 10px auto 20px auto;
	}

	.g-recaptcha {
		transform: scale(0.75);
		/* thu nhỏ 85% */
	}

	.my-custom-button {
		background-color: #31B0D5 !important;
		/* Màu cam đỏ */
		color: white !important;
		/* Chữ màu trắng */
		border-radius: 5px !important;
		/* Bo góc */
		padding: 10px 20px !important;
		font-size: 16px !important;
	}

	.custom-toast {
		width: 90% !important;
		max-width: none !important;
		font-size: 1.1rem !important;
		padding: 1rem 1.5rem !important;
		left: 50% !important;
		transform: translateX(-50%) !important;
	}

	.swal2-success-toast {
		background-color: #d4edda !important;
		color: #155724 !important;
		border: 1px solid #c3e6cb;
	}

	.swal2-error-toast {
		background-color: #f8d7da !important;
		color: #721c24 !important;
		border: 1px solid #f5c6cb;
	}

	/* Loại bỏ conflict tailwind */
	.collapse {
		visibility: unset !important;
	}

	a {
		color: #337ab7 !important;
		text-decoration: none !important;
	}

	a:hover {
		text-decoration: none !important;
	}

	.navbar-info .navbar-nav>.active>a,
	.navbar-info .navbar-nav>.active>a:hover,
	.navbar-info .navbar-nav>.active>a:focus {
		color: #fff !important;
		background-color: #4c66a4 !important;
	}

	.navbar-info .navbar-nav>li>a:hover,
	.navbar-info .navbar-nav>li>a:focus {
		color: #fff !important;
		background-color: #337ab7 !important;
		border-top-left-radius: 4px !important;
		border-top-right-radius: 4px !important;
	}

	a.product_title:hover {
		color: #337ab7 !important;
	}

	.dropdown-menu>li>a {
		color: #333 !important;
	}

	@media (min-width: 1200px) {
		.container {
			width: 1170px !important;
		}
	}

	/* custom cho đăng ký | đổi mật khẩu */
	a:hover,
	a:focus {
		color: #23527c !important;
		text-decoration: underline !important;
	}

	.text-blue-600 a {
		--tw-text-opacity: 1 !important;
		color: rgb(37 99 235 / var(--tw-text-opacity, 1)) !important;
	}

	#google-card {
		background-color: white !important;
		/* nền trắng */
		color: black !important;
		/* chữ đen */
		font-size: 16px !important;
		font-weight: 600 !important;
		font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji" !important;
		text-decoration: none !important;
		line-height: 1.5 !important;
		letter-spacing: 0.5px !important;
		padding: 0.5rem 1rem !important;
		/* tương đương py-2 px-4 */
		border-radius: 0.375rem !important;
		/* tương đương rounded-md */
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		gap: 0.5rem !important;
		/* tương đương gap-2 */
		transition: background-color 0.2s ease !important;
		border: 1px solid #ccc !important;
		/* viền nhẹ */
	}

	#google-card:hover {
		background-color: #f0f0f0 !important;
		/* nền hover sáng hơn */
		color: black !important;
	}
</style>

<body>
	<div class="container">
		<?php $this->load->view('site/header', $this->data); ?>

		<div class="col-12 clearpaddingr">
			<ol class="breadcrumb">
				<li><a href="<?php echo base_url(); ?>#"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Trang chủ</a></li>
				<li class="active">Đăng nhập</li>
			</ol>

			<div class="flex flex-col items-center">
				<div class="mb-10 mt-[70px]">
					<label class="block text-gray-700 mb-4 text-2xl" for="name">Email</label>
					<input type="email" id="email" name="email"
						class="w-[400px] p-3 border border-gray-300 rounded text-lg">
				</div>

				<div class="mb-10">
					<label class="block text-gray-700 mb-4 text-2xl" for="password">Mật khẩu</label>
					<input type="password" id="password" name="password"
						class="w-[400px] p-3 border border-gray-300 rounded text-lg">
				</div>

				<a
					href="<?php echo htmlspecialchars($login_url); ?>" id="google-card"
					class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
					<img
						src="https://developers.google.com/identity/images/g-logo.png"
						class="w-7 h-7"
						alt="Google Logo" />
					<span>Đăng nhập bằng Google</span>
				</a>

				<div class="g-recaptcha form-group" style="margin: 0;" data-sitekey="6LcKdPUqAAAAAGv-BwfXyqkrqpTuVEUCQLGwbG6Z" data-callback="onCaptchaSuccess"></div>

				<button id="submitBtn"
					class="w-[400px] text-white text-2xl font-semibold py-3 rounded-lg transition mb-10 duration-300"
					style="background-color: rgb(61, 177, 212);"
					onmouseover="this.style.backgroundColor='rgb(39, 147, 180)'"
					onmouseout="this.style.backgroundColor='rgb(61, 177, 212)'">
					Đăng nhập
				</button>

				<div class="text-center text-2xl text-blue-600 space-x-4 mb-[100px]">
					<a href="/dang-ky" class="hover:underline">Đăng ký</a>
					<span>|</span>
					<a href="/quen-mat-khau" class="hover:underline">Quên mật khẩu?</a>
				</div>
			</div>
		</div>

		<?php $this->load->view('site/footer', $this->data); ?>
	</div>
	<script src="<?php echo public_url('site/'); ?>js/login.js"></script>
	<script src="<?php echo public_url('site/'); ?>bootstrap/js/bootstrap.min.js"></script>
</body>

</html>