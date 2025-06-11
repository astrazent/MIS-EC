<style>
/* Ensure footer spans full width */
.site-footer {
    width: 100%;
    background-color: #202327;
    color: #fff;
    padding: 50px 0 20px;
    margin-top: 30px;
}
.site-footer .container-fluid {
    max-width: 1400px;
    padding-left: 15px;
    padding-right: 15px;
    margin: 0 auto;
}
</style>
<footer class="site-footer">
	<div class="container-fluid">
		<div class="footer-top">
			<div class="row">
				<div class="col-md-3 col-sm-6">
					<div class="footer-widget">
						<h4 class="widget-title">Giới thiệu</h4>
						<p>Ngọc Lan là thương hiệu thời trang hiện đại cung cấp quần áo và phụ kiện chất lượng cao cho nam và nữ.</p>
						<div class="social-links">
							<a href="#"><i class="fab fa-facebook-f"></i></a>
							<a href="#"><i class="fab fa-instagram"></i></a>
							<a href="#"><i class="fab fa-twitter"></i></a>
							<a href="#"><i class="fab fa-pinterest-p"></i></a>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="footer-widget">
						<h4 class="widget-title">Liên kết khác</h4>
						<ul class="footer-menu">
							<li><a href="#">Giới thiệu</a></li>
							<li><a href="#">Câu hỏi thường gặp</a></li>
							<li><a href="#">Đổi trả & Hoàn tiền</a></li>
							<li><a href="#">Liên hệ với chúng tôi</a></li>
						</ul>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="footer-widget">
						<h4 class="widget-title">Danh mục</h4>
						<ul class="footer-menu">
							<li><a href="<?php echo base_url('thoi-trang-nu-c8'); ?>">Nữ</a></li>
							<li><a href="<?php echo base_url('thoi-trang-nam-c7'); ?>">Nam</a></li>
							<li><a href="<?php echo base_url('quan-ao-gia-dinh-c9'); ?>">Gia đình</a></li>
							<li><a href="<?php echo base_url('khuyen-mai'); ?>">Khuyến mãi</a></li>
						</ul>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="footer-widget">
						<h4 class="widget-title">Thông tin liên hệ</h4>
						<ul class="contact-info">
							<li><i class="fas fa-map-marker-alt"></i> Kỳ Sơn - Thủy Nguyên - Hải Phòng</li>
							<li><i class="fas fa-phone"></i> 01215345336</li>
							<li><i class="fas fa-envelope"></i> info@ngoclan.com</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="footer-bottom">
			<div class="row">
				<div class="col-md-6">
					<p class="copyright">© <?php echo date('Y'); ?> Ngọc Lan.</p>
				</div>
				<!-- <div class="col-md-6">
					<div class="payment-methods">
						<img src="<?php echo base_url('public/site/images/payment-methods.png'); ?>" alt="Phương thức thanh toán" class="img-fluid">
					</div>
				</div> -->
			</div>
		</div>
	</div>
</footer> 