<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
<style>
	.my-custom-button {
		background-color: #31B0D5;
		color: white;
		border-radius: 5px;
		padding: 10px 20px;
		font-size: 16px;
	}

	.shipping-detail {
		display: none;
		background-color: #f8f8f8;
		border: 1px solid #ddd;
		padding: 15px;
		border-radius: 8px;
		width: 100%;
		margin-top: 20px;
		font-family: Arial, sans-serif;
		color: #333;
		font-size: 85%;
	}

	.shipping-item {
		display: flex;
		justify-content: space-between;
		margin-bottom: 10px;
	}

	.shipping-item span {
		display: inline-block;
	}

	.shipping-item .distance,
	.shipping-item .fee {
		text-align: right;
		font-weight: bold;
		color: #d9534f;
		background-color: #f9f2f4;
		padding: 4px 8px;
		border-radius: 4px;
		margin-left: 10px;
	}

	.shipping-item strong {
		font-weight: bold;
	}

	.shipping,
	.cart-voucher,
	.giftcode {
		display: none;
	}

	.voucher-card {
		display: flex;
		justify-content: space-between;
		align-items: center;
		font-size: smaller;
	}

	.voucher-text {
		flex: 1;
		font-size: smaller;
	}

	.shipping-icon {
		width: 42px;
		height: auto;
	}

	.sale-icon {
		width: 42px;
		height: auto;
	}

	.voucher-tooltip {
		position: fixed;
		top: 0;
		left: 0;
		z-index: 1000;
		opacity: 0;
		visibility: hidden;
		transform: translateY(-10px);
		transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s;
		pointer-events: none;
	}

	.voucher-tooltip.show {
		opacity: 1;
		visibility: visible;
		transform: translateY(0);
		pointer-events: auto;
	}

	.btn-danger,
	.btn-success {
		color: #fff;
	}

	.collapse {
		visibility: unset;
	}

	a:hover {
		text-decoration: none;
	}

	.navbar-info .navbar-nav>.active>a,
	.navbar-info .navbar-nav>.active>a:hover,
	.navbar-info .navbar-nav>.active>a:focus {
		color: #fff;
		background-color: #4c66a4;
	}

	.navbar-info .navbar-nav>li>a:hover,
	.navbar-info .navbar-nav>li>a:focus {
		color: #fff;
		background-color: #337ab7;
		border-top-left-radius: 4px;
		border-top-right-radius: 4px;
	}

	a.product_title:hover {
		color: #337ab7;
	}

	.dropdown-menu>li>a {
		color: #333;
	}
	#submitBtn:hover, #apply-gift:hover, #cancel-gift:hover  {
		color: black !important;
		background-color: white !important;
	}
	#submitBtn, #apply-gift, #cancel-gift {
		color: white !important;
		background-color: black !important;
	}
	@media (min-width: 1200px) {
		.container {
			width: 1170px;
		}
	}
</style>

<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 clearpaddingr">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearpadding">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><i class="fas fa-home"></i> Trang chủ</a></li>
			<li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
		</ol>
		
		<div class="bg-white p-3 rounded shadow text-base mb-6">
			<h2 class="text-2xl font-semibold mb-6">Thông tin giao hàng</h2>

			<div class="mb-6">
				<label class="block text-gray-700 mb-2" for="name">Họ và tên</label>
				<input type="text" id="name" name="name" style="font-size:85%;" class="w-full p-2 border border-gray-300 rounded" value="">
			</div>

			<div class="mb-6">
				<label class="block text-gray-700 mb-2" for="email">Email</label>
				<input type="email" id="email" name="email" style="font-size:85%;" class="w-full p-2 border border-gray-300 rounded" placeholder="example@gmail.com" value="">
			</div>

			<div class="mb-6">
				<label class="block text-gray-700 mb-2" for="phone">Số điện thoại</label>
				<input type="text" id="phone" name="phone" style="font-size:85%;" class="w-full p-2 border border-gray-300 rounded" value="">
			</div>

			<div class="mb-6">
				<label class="block text-gray-700 mb-2" for="address">Địa chỉ</label>
				<input type="text" id="address" name="address" style="font-size:85%;" class="w-full p-2 border border-gray-300 rounded" placeholder="VD: 208-E5" value="">
			</div>

			<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
				<div>
					<label class="block text-gray-700 mb-2" for="city">Tỉnh/Thành phố</label>
					<select id="city" name="city" style="font-size:85%;" class="w-full p-2 border border-gray-300 rounded">
						<option value="" selected></option>
					</select>
				</div>

				<div>
					<label class="block text-gray-700 mb-2" for="district">Quận/Huyện</label>
					<select id="district" name="district" style="font-size:85%;" class="w-full p-2 border border-gray-300 rounded">
						<option value="" selected></option>
					</select>
				</div>
				
				<div>
					<label class="block text-gray-700 mb-2" for="ward">Phường/Xã</label>
					<select id="ward" name="ward" style="font-size:85%;" class="w-full p-2 border border-gray-300 rounded">
						<option value="" selected></option>
					</select>
				</div>
			</div>

			<div class="mb-6">
				<label class="block text-gray-700 mb-2" for="message">Ghi chú</label>
				<input type="text" id="message" name="message" style="font-size:85%;" placeholder="Bạn có ghi chú gì cho cửa hàng không?" class="w-full p-2 border border-gray-300 rounded" value="">
			</div>

			<h3 class="text-lg font-semibold mb-3">Phí vận chuyển</h3>
			<div class="p-3 bg-gray-100 border border-gray-300 mb-6 rounded">
				<p class="text-gray-700" style="font-size:85%;">Các tỉnh thành thuộc khu vực miễn phí giao hàng & lắp đặt sẽ được Ngọc Lan liên hệ báo sau.</p>
				<div class="shipping-detail">
					<div class="shipping-item">
						<span><strong>Khoảng cách:</strong></span>
						<span class="distance"></span>
					</div>
					<div class="shipping-item">
						<span><strong>Phí vận chuyển:</strong></span>
						<span class="fee"></span>
					</div>
				</div>
			</div>

			<h2 class="text-lg font-semibold mb-3">Phương thức thanh toán</h2>
			<div id="payment-error" class="text-red-500 text-sm hidden">Vui lòng chọn phương thức thanh toán</div>
			<div class="space-y-4 mb-6">
				<div class="p-4 border border-gray-300 rounded-lg">
					<label class="flex items-center space-x-3">
						<input class="form-radio text-blue-600 w-4 h-4 payment-option" name="payment" type="radio" value="cash" />
						<i class="fas fa-money-check-alt text-black-600 text-2xl"></i>
						<span class="text-base font-medium payment-title">Thanh toán tiền mặt khi nhận hàng</span>
					</label>
					<div id="bank-details" class="hidden mt-2 text-gray-700 text-sm">
						<p><strong>Tên tài khoản:</strong> Công Ty Cổ Phần Hợp Tác Kinh Tế Và Xuất Nhập Khẩu Savimex</p>
						<p><strong>Số tài khoản:</strong> 0071001303667</p>
						<p><strong>Ngân hàng:</strong> Vietcombank – CN HCM</p>
						<p><strong>Nội dung:</strong> Tên + SĐT đặt hàng</p>
					</div>
				</div>

				<div class="p-4 border border-gray-300 rounded-lg">
					<label class="flex items-center space-x-3">
						<input class="form-radio text-blue-600 w-4 h-4 payment-option" name="payment" type="radio" value="pos" />
						<i class="fas fa-credit-card text-black-600 text-2xl"></i>
						<span class="text-base font-medium payment-title">Thanh toán quẹt thẻ khi giao hàng (POS)</span>
					</label>
					<div id="pos-details" class="hidden mt-2 text-gray-700 text-sm">
						<p>Thanh toán bằng thẻ qua máy POS tại nhà (nhân viên giao hàng sẽ đem theo máy POS) khi giao hàng.</p>
					</div>
				</div>

				<div class="p-4 border border-gray-300 rounded-lg">
					<label class="flex items-center space-x-3">
						<input class="form-radio text-blue-600 w-4 h-4 payment-option" name="payment" type="radio" value="vnpay" />
						<img class="w-8 h-8" src="https://hstatic.net/0/0/global/design/seller/image/payment/vnpay_new.svg?v=6" />
						<span class="text-base font-medium payment-title">Thanh toán online qua cổng VNPay</span>
					</label>
					<div id="vnpay-details" class="hidden mt-2 text-gray-700 text-sm">
						<p>Hỗ trợ thanh toán qua ATM/Visa/MasterCard/JCB/QR Pay.</p>
						<div class="mt-2 flex space-x-2">
							<img class="w-32 h-16" src="https://hstatic.net/0/0/global/design/seller/image/payment/atm_visa_master_jcb.svg?v=6" />
						</div>
					</div>
				</div>

				<div class="p-4 border border-gray-300 rounded-lg">
					<label class="flex items-center space-x-3">
						<input class="form-radio text-blue-600 w-4 h-4 payment-option" name="payment" type="radio" value="vietqr" />
						<img class="w-8 h-8" src="https://vietqr.net/vietqr_ico.png" />
						<span class="text-base font-medium payment-title">Thanh toán chuyển khoản bằng VietQR</span>
					</label>
					<div id="vietqr-details" class="hidden mt-2 text-gray-700 text-sm" style="margin-top: 20px;">
						<img src="https://qr.sepay.vn/img?acc=VQRQABTXE3594&bank=MBBank&amount=<?php echo $this->data['total_amount'] ?>&des=DH102969" width="120" height="120">
						<div style="margin-top: 15px;">
							<p><strong>Tên tài khoản:</strong> Phan Gia Nguyên</p>
							<p><strong>Số tài khoản:</strong> 819898898</p>
							<p><strong>Ngân hàng:</strong> MBBank</p>
							<p><strong>Nội dung:</strong> Tên + SĐT đặt hàng</p>
						</div>
					</div>
				</div>
			</div>

			<h2 class="text-lg font-semibold mb-3">Quà tặng khách hàng</h2>
			<div class="space-y-4 mb-6">
				<div class="p-4 border border-gray-300 rounded-lg">
					<label class="block text-base font-medium mb-3 text-gray-800">Chọn voucher</label>
					<div id="voucher-slider" class="flex overflow-x-auto space-x-4 pb-2">
					</div>
					<div id="tooltip-portal"></div>

					<div id="selected-voucher" class="mt-3 hidden text-sm">
						<div class="flex items-center justify-between bg-blue-50 p-2 border border-blue-300 rounded-md">
							<span><span id="selected-voucher-name"></span></span>
							<button id="remove-selected-voucher" class="text-red-500 hover:underline">Bỏ chọn</button>
						</div>
					</div>

					<hr class="my-4">

					<label class="block text-base font-medium mb-3 text-gray-800">Gift Code</label>
					<div class="flex items-center space-x-3">
						<input
							type="text"
							name="gift_code"
							id="gift_code"
							placeholder="Nhập gift code..."
							class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
						<button
							type="button"
							id="apply-gift"
							class="px-4 py-2 border border-black bg-black-600 text-white text-sm rounded-lg hover:bg-blue-700 transition whitespace-nowrap">
							Áp dụng
						</button>
						<button
							type="button"
							id="cancel-gift"
							class="px-4 py-2 border border-black bg-gray-400 text-white text-sm rounded-lg hover:bg-gray-500 transition hidden">
							Huỷ
						</button>
					</div>
					<div id="gift-message" class="mt-2 text-sm text-green-600 hidden"></div>
				</div>
				
				<div id="applied-summary" class="mt-4 space-y-3 hidden">
					<div class="flex items-center justify-between bg-blue-50 p-2 border border-blue-300 rounded-md">
						<span><strong>Voucher:</strong> <span id="applied-voucher-code"></span></span>
						<button id="remove-voucher-btn" class="text-red-500 hover:underline">Bỏ chọn</button>
					</div>
				</div>
			</div>

			<div class="mt-6 mb-6 text-base font-semibold space-y-3">
				<div class="flex justify-between items-center">
					<span>Tiền hàng:</span>
					<span class="text-gray-800"><?php echo number_format($this->data['total_amount'], 0, ',', '.') ?> VND</span>
				</div>

				<div class="flex justify-between items-center shipping">
					<span>Phí vận chuyển:</span>
					<span class="text-gray-800" id="shippingFee"></span>
				</div>

				<div class="flex justify-between items-center cart-voucher">
					<span>Sau khi áp voucher:</span>
					<span class="text-red-500" id="cartVoucher"></span>
				</div>

				<div class="flex justify-between items-center giftcode">
					<span>Sau khi áp gift code:</span>
					<span class="text-red-500" id="giftcode"></span>
				</div>

				<div class="flex justify-between items-center border-t pt-3 text-red-600">
					<span>Tổng thanh toán:</span>
					<span id="totalPrice" data-price='<?php echo $this->data['total_amount'] ?>'>
						<?php echo number_format($this->data['total_amount'], 0, ',', '.') ?> VND
					</span>
				</div>
			</div>

			<button type="button" id="submitBtn"
				class="w-full bg-white text-black border border-black 
				hover:bg-black hover:text-white 
				font-medium uppercase tracking-wide py-3 px-6 transition-all duration-300 ease-in-out 
				rounded mt-4 text-lg">
				<i class="fas fa-check-circle me-2"></i>Hoàn tất đơn hàng
			</button>

		</div>
	</div>
</div>
<div id="userInfo" style="display:none;" data-user='<?php echo json_encode($user); ?>'>
	<div id="cartInfo" style="display:none;" data-cart='<?php echo json_encode($carts_info); ?>'>
		<div id="openroute" data-key='<?php echo getenv('OPENROUTE_SERVICE'); ?>' style="display: none;"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
<script src="<?php echo public_url('site/'); ?>js/order.js"></script>
