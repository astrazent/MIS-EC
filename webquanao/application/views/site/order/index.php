<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
<style>
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
</style>
<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 clearpaddingr">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearpadding">
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url(); ?>#"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Trang chủ</a></li>
			<li class="active">Thanh toán</li>
		</ol>
		<div class="bg-white p-6 rounded shadow text-3xl mb-20">
			<h2 class="text-4xl font-semibold mb-10">Thông tin giao hàng</h2>
			<?php if (!isset($user)): ?>
			<p class="mb-10 text-2xl">Quý khách đã có tài khoản? <a href="<?php echo base_url('dang-nhap'); ?>" class="text-blue-600">Đăng nhập</a></p>
			<?php else: ?>
			<p class="mb-10 text-2xl">Xin chào, <strong><?php echo $user->name; ?></strong>!</p>
			<?php endif; ?>
			
			<?php if ($this->session->flashdata('error')): ?>
			<div class="p-4 mb-4 bg-red-100 text-red-700 border border-red-300 rounded">
				<?php echo $this->session->flashdata('error'); ?>
			</div>
			<?php endif; ?>
			
			<?php if ($this->session->flashdata('success')): ?>
			<div class="p-4 mb-4 bg-green-100 text-green-700 border border-green-300 rounded">
				<?php echo $this->session->flashdata('success'); ?>
			</div>
			<?php endif; ?>
			
			<?php 
			// Display validation errors if any
			$errors = $this->session->flashdata('errors');
			if (!empty($errors) && is_array($errors)): ?>
			<div class="p-4 mb-4 bg-red-100 text-red-700 border border-red-300 rounded">
				<ul class="list-disc ml-4">
					<?php foreach ($errors as $field => $error): ?>
					<li><?php echo $error; ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>

			<form method="POST" action="<?php echo base_url('order/complete'); ?>" id="orderForm">
				<div class="mb-10">
					<label class="block text-gray-700 mb-4" for="name">Họ và tên</label>
					<input type="text" id="name" name="name" style="font-size:85% !important;" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($user) ? $user->name : ''; ?>" required>
				</div>

				<div class="mb-10">
					<label class="block text-gray-700 mb-4" for="email">Email</label>
					<input type="email" id="email" name="email" style="font-size:85% !important;" class="w-full p-2 border border-gray-300 rounded" placeholder="example@gmail.com" value="<?php echo isset($user) ? $user->email : ''; ?>" required>
				</div>

				<div class="mb-10">
					<label class="block text-gray-700 mb-4" for="phone">Số điện thoại</label>
					<input type="text" id="phone" name="phone" style="font-size:85% !important;" class="w-full p-2 border border-gray-300 rounded" value="<?php echo isset($user) ? $user->phone : ''; ?>" required>
				</div>

				<div class="mb-10">
					<label class="block text-gray-700 mb-4" for="address">Địa chỉ</label>
					<input type="text" id="address" name="address" style="font-size:85% !important;" class="w-full p-2 border border-gray-300 rounded" placeholder="VD: 208-E5" value="<?php echo isset($user) ? $user->address : ''; ?>" required>
				</div>

				<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
					<div>
						<label class="block text-gray-700 mb-4" for="city">Tỉnh/Thành</label>
						<select id="city" name="city" style="font-size:85% !important;" class="w-full p-2 border border-gray-300 rounded" required>
							<option value="" selected></option>
						</select>
					</div>

					<div>
						<label class="block text-gray-700 mb-4" for="district">Quận/Huyện</label>
						<select id="district" name="district" style="font-size:85% !important;" class="w-full p-2 border border-gray-300 rounded" required>
							<option value="" selected></option>
						</select>
					</div>
					<div>
						<label class="block text-gray-700 mb-4" for="ward">Phường/Xã</label>
						<select id="ward" name="ward" style="font-size:85% !important;" class="w-full p-2 border border-gray-300 rounded" required>
							<option value="" selected></option>
						</select>
					</div>
				</div>

				<div class="mb-10">
					<label class="block text-gray-700 mb-4" for="message">Ghi chú</label>
					<input type="text" id="message" name="message" style="font-size:85% !important;" placeholder="Bạn có ghi chú gì cho cửa hàng không?" class="w-full p-2 border border-gray-300 rounded" value="">
				</div>

				<h3 class="text-3xl font-semibold mb-4">Phí vận chuyển</h3>
				<div class="p-4 bg-gray-100 border border-gray-300 mb-10 rounded">
					<p class="text-gray-700" style="font-size:85% !important;">Các tỉnh thành không thuộc khu vực miễn phí giao hàng & lắp đặt, phí giao hàng sẽ được Ngọc Lan liên hệ báo sau.</p>
				</div>

				<h2 class="text-3xl font-semibold mb-4">Phương thức thanh toán</h2>
				<div id="payment-error" class="text-red-500 text-xl hidden">Vui lòng chọn phương thức thanh toán</div>
				<div class="space-y-6">
					<div class="p-6 border border-gray-300 rounded-lg">
						<label class="flex items-center space-x-4">
							<input class="form-radio text-blue-600 w-6 h-6 payment-option" name="payment" type="radio" value="cash" required />
							<i class="fas fa-money-check-alt text-blue-600 text-4xl"></i>
							<span class="text-2xl font-medium payment-title">Thanh toán tiền mặt khi nhận hàng</span>
						</label>
						<div id="bank-details" class="hidden mt-2 text-gray-700 text-2xl">
							<p><strong>Tên tài khoản:</strong> Công Ty Cổ Phần Hợp Tác Kinh Tế Và Xuất Nhập Khẩu Savimex</p>
							<p><strong>Số tài khoản:</strong> 0071001303667</p>
							<p><strong>Ngân hàng:</strong> Vietcombank – CN HCM</p>
							<p><strong>Nội dung:</strong> Tên + SĐT đặt hàng</p>
						</div>
					</div>

					<div class="p-6 border border-gray-300 rounded-lg">
						<label class="flex items-center space-x-4">
							<input class="form-radio text-blue-600 w-6 h-6 payment-option" name="payment" type="radio" value="pos" />
							<i class="fas fa-credit-card text-blue-600 text-4xl"></i>
							<span class="text-2xl font-medium payment-title">Thanh toán quẹt thẻ khi giao hàng (POS)</span>
						</label>
						<div id="pos-details" class="hidden mt-2 text-gray-700 text-2xl">
							<p>Thanh toán bằng thẻ qua máy POS tại nhà (nhân viên giao hàng sẽ đem theo máy POS) khi giao hàng.</p>
						</div>
					</div>

					<div class="p-6 border border-gray-300 rounded-lg">
						<label class="flex items-center space-x-4">
							<input class="form-radio text-blue-600 w-6 h-6 payment-option" name="payment" type="radio" value="vnpay" />
							<img class="w-12 h-12" src="https://hstatic.net/0/0/global/design/seller/image/payment/vnpay_new.svg?v=6" />
							<span class="text-2xl font-medium payment-title">Thanh toán online qua cổng VNPay</span>
						</label>
						<div id="vnpay-details" class="hidden mt-2 text-gray-700 text-2xl">
							<p>Hỗ trợ thanh toán qua ATM/Visa/MasterCard/JCB/QR Pay.</p>
							<div class="mt-2 flex space-x-2">
								<img class="w-54 h-27" src="https://hstatic.net/0/0/global/design/seller/image/payment/atm_visa_master_jcb.svg?v=6" />
							</div>
						</div>
					</div>

					<div class="p-6 border border-gray-300 rounded-lg">
						<label class="flex items-center space-x-4">
							<input class="form-radio text-blue-600 w-6 h-6 payment-option" name="payment" type="radio" value="vietqr" />
							<img class="w-12 h-12" src="https://vietqr.net/vietqr_ico.png" />
							<span class="text-2xl font-medium payment-title">Thanh toán chuyển khoản bằng VietQR</span>
						</label>
						<div id="vietqr-details" class="hidden mt-2 text-gray-700 text-2xl" style="margin-top: 20px !important;">
							<img src="https://qr.sepay.vn/img?acc=VQRQABTXE3594&bank=MBBank&amount=<?php echo $this->data['total_amount'] ?>&des=DH102969" width="150" height="150">
							<div style="margin-top: 20px;">
								<p><strong>Tên tài khoản:</strong> Phan Gia Nguyên</p>
								<p><strong>Số tài khoản:</strong> 819898898</p>
								<p><strong>Ngân hàng:</strong> MBBank</p>
								<p><strong>Nội dung:</strong> Tên + SĐT đặt hàng</p>
							</div>
						</div>
					</div>
				</div>

				<div class="flex justify-between items-center mt-10 mb-10 text-3xl font-semibold">
					<span>Tổng hóa đơn:</span>
					<span id="totalPrice" class="text-red-600"><?php echo $this->data['total_amount'] ?> VND</span>
				</div>

				<button type="submit" class="w-full text-white text-3xl font-medium py-3 rounded-lg transition duration-300"
					style="background-color: rgb(61, 177, 212);" id="submitBtn"
					onmouseover="this.style.backgroundColor='rgb(39, 147, 180)'"
					onmouseout="this.style.backgroundColor='rgb(61, 177, 212)'">
					Hoàn tất đơn hàng
				</button>
			</form>
		</div>
	</div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"> </script>

<script>
// Define base URL from PHP for use in JavaScript
var baseUrl = "<?php echo base_url(); ?>";
var completeEndpoint = "<?php echo base_url('order/complete'); ?>";
var paymentEndpoint = "<?php echo base_url('order/payment'); ?>";
</script>

<script src="<?php echo public_url('site/'); ?>js/order.js"></script>