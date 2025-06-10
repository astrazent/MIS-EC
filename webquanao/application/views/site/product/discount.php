<script>
	gtag('config', 'G-QPG3ZQV73K', {
		'page_title': 'Trang khuyến mại',
		'page_path': '/khuyen-mai'
	});
</script>
<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 clearpaddingr">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearpadding">
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url(); ?>"><i class="fas fa-home"></i> Trang chủ</a></li>
			<li class="active">Sản phẩm khuyến mại</li>
		</ol>

		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">Sản phẩm khuyến mại</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<?php foreach ($product_list as $value) {
						$name = covert_vi_to_en($value->name);
						$name = strtolower($name);
					?>
						<div class="col-md-3 col-sm-6 col-xs-6 product-column">
							<div class="product-item">
								<div class="product-label">Sale</div>
								<div class="product-item-image">
									<a href="<?php echo base_url($name . '-p' . $value->id); ?>">
										<img src="<?php echo base_url(); ?>upload/product/<?php echo $value->image_link; ?>" alt="<?php echo $value->name; ?>">
									</a>
								</div>
								<div class="product-item-content">
									<h3 class="product-item-title">
										<a href="<?php echo base_url($name . '-p' . $value->id); ?>"><?php echo $value->name; ?></a>
									</h3>
									
									<?php if ($value->discount > 0 || $value->price < $value->origin_price) {
										$new_price = $value->price - $value->discount; ?>
										<div class="product-item-price">
											<?php echo number_format($new_price); ?> VNĐ
											<del><?php echo number_format($value->price); ?> VNĐ</del>
										</div>
									<?php } else { ?>
										<div class="product-item-price">
											<?php echo number_format($value->origin_price); ?> VNĐ
										</div>
									<?php } ?>
									
									<div class="product-meta">
										<span class="view-count"><i class="fas fa-eye" aria-hidden="true" title="Số lượt xem"></i> <?php echo $value->view; ?></span>
										<span class="buy-count"><i class="fas fa-shopping-cart" aria-hidden="true" title="Số lượng đặt mua"></i> <?php echo $value->buyed; ?></span>
									</div>
									
									<div class="product-item-actions">
										<a href="<?php echo base_url('cart/add/' . $value->id); ?>" class="btn btn-primary add-to-cart-btn">
											<i class="fas fa-shopping-cart me-2"></i> THÊM GIỎ HÀNG
										</a>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
				<div class="catalog-pagination">
					<?php echo $this->pagination->create_links(); ?>
					<?php if(!$this->pagination->create_links()): ?>
						<ul class="pagination">
							<li class="page-item active"><span class="page-link">1</span></li>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>