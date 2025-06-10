<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 clearpaddingr">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearpadding">
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url(); ?>"><i class="fas fa-home"></i> Trang chủ</a></li>
			<li class="active">
				<?php 
				// Xác định tiêu đề trang dựa vào URL hiện tại
				$current_url = current_url();
				if (strpos($current_url, 'thoi-trang-nam') !== false) {
					echo 'Thời trang nam';
				} elseif (strpos($current_url, 'thoi-trang-nu') !== false) {
					echo 'Thời trang nữ';
				} elseif (strpos($current_url, 'quan-ao-gia-dinh') !== false) {
					echo 'Quần áo gia đình';
				} else {
					echo 'Sản phẩm mới nhất';
				}
				?>
			</li>
		</ol>

		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">
					<?php 
					// Xác định tiêu đề panel dựa vào URL hiện tại
					$current_url = current_url();
					if (strpos($current_url, 'thoi-trang-nam') !== false) {
						echo 'Thời trang nam';
					} elseif (strpos($current_url, 'thoi-trang-nu') !== false) {
						echo 'Thời trang nữ';
					} elseif (strpos($current_url, 'quan-ao-gia-dinh') !== false) {
						echo 'Quần áo gia đình';
					} else {
						echo 'Sản phẩm mới nhất';
					}
					?>
				</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<?php foreach ($product_list as $value) {
						$name = covert_vi_to_en($value->name);
						$name = strtolower($name);
					?>
						<div class="col-md-3 col-sm-6 col-xs-6 product-column">
							<div class="product-item">
								<div class="product-label">
									<?php 
									// Xác định tiêu đề panel dựa vào URL hiện tại
									$current_url = current_url();
									if (strpos($current_url, 'thoi-trang-nam') !== false) {
										echo 'Men';
									} elseif (strpos($current_url, 'thoi-trang-nu') !== false) {
										echo 'Women';
									} elseif (strpos($current_url, 'quan-ao-gia-dinh') !== false) {
										echo 'Family';
									} else {
										echo 'Sản phẩm mới nhất';
									}
									?>
								</div>
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
								<!-- <?php if ($value->discount > 0 || $value->price < $value->origin_price) {
									$new_price = $value->price - $value->discount; ?>
									<p><span class='price text-right'><?php echo number_format($new_price); ?> VNĐ</span> <del class="product-discount"><?php echo number_format($value->price); ?> VNĐ</del></p>
								<?php } else { ?>
									<p><span class='price text-right'><?php echo number_format($value->origin_price); ?> VNĐ</span></p>
								<?php	} ?>
								<p><span class="glyphicon glyphicon-eye-open" aria-hidden="true" title="Số lượt xem"></span> <?php echo $value->view; ?> <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true" title="Số lượng đặt mua"><?php echo $value->buyed; ?></p>
								<a href="<?php echo base_url('cart/add/' . $value->id); ?>"><button class='btn btn-info'><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Thêm giỏ hàng</button></a> -->
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