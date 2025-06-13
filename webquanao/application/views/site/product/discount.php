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
						<div class="col-md-3 col-sm-6 col-xs-6 product-column mb-4">
							<div class="product-item card h-100">
								<div class="product-label">Sale</div>
								<div class="product-item-image">
									<a href="<?php echo base_url($name . '-p' . $value->id); ?>">
										<img src="<?php echo base_url('upload/product/' . $value->image_link); ?>" alt="<?php echo $value->name; ?>" class="card-img-top">
									</a>
								</div>
								<div class="product-item-content card-body">
									<h5 class="product-item-title card-title">
										<a href="<?php echo base_url($name . '-p' . $value->id); ?>"><?php echo $value->name; ?></a>
									</h5>
									
									<?php if ($value->discount > 0 || $value->price < $value->origin_price) {
										$new_price = $value->price - $value->discount; ?>
										<div class="product-item-price">
											<span class="price-value"><?php echo number_format($new_price); ?> VNĐ</span>
											<del class="text-muted"><?php echo number_format($value->price); ?> VNĐ</del>
										</div>
									<?php } else { ?>
										<div class="product-item-price">
											<span class="price-value"><?php echo number_format($value->origin_price); ?> VNĐ</span>
										</div>
									<?php } ?>
									
									<div class="product-meta mt-2">
										<span class="view-count me-3"><i class="fas fa-eye" aria-hidden="true" title="Số lượt xem"></i> <?php echo $value->view; ?></span>
										<span class="buy-count"><i class="fas fa-shopping-cart" aria-hidden="true" title="Số lượng đặt mua"></i> <?php echo $value->buyed; ?></span>
									</div>
									
									<div class="product-item-actions mt-3">
										<a href="<?php echo base_url('cart/add/' . $value->id); ?>" class="btn btn-primary add-to-cart-btn">
											<i class="fas fa-shopping-cart me-2"></i> THÊM GIỎ HÀNG
										</a>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
				
				<!-- Phân trang -->
				<?php if(isset($total) && $total > 8): ?>
				<div class="catalog-pagination my-4 text-center">
					<?php 
					$pagination_links = isset($this->pagination) ? $this->pagination->create_links() : '';
					if (!empty($pagination_links)): ?>
						<div class="pagination-container">
							<?php echo $pagination_links; ?>
						</div>
					<?php else: ?>
						<nav aria-label="Phân trang">
							<ul class="pagination justify-content-center">
								<li class="page-item active">
									<span class="page-link">1</span>
								</li>
							</ul>
						</nav>
					<?php endif; ?>
				</div>
				<?php endif; ?>
				
				<style>
					/* Cải thiện hiển thị phân trang */
					.pagination {
						display: flex;
						justify-content: center;
						margin: 20px 0;
					}
					.pagination > li {
						margin: 0 5px;
						display: inline-block;
					}
					.pagination > li > a, 
					.pagination > li > span {
						border-radius: 0;
						color: #333;
						padding: 10px 15px;
						border: 1px solid #ddd;
						text-decoration: none;
						display: inline-block;
						min-width: 40px;
						text-align: center;
						font-weight: 500;
						background-color: #fff;
					}
					.pagination > li.active > a, 
					.pagination > li.active > span {
						background-color: black;
						border-color: black;
						color: white;
					}
					.pagination > li > a:hover,
					.pagination > li > span:hover {
						background-color: #f5f5f5;
						color: #333;
						border-color: #ddd;
					}
					/* Style cho nút Next (>) */
					.pagination > li:last-child > a,
					.pagination > li:last-child > span {
						font-weight: bold;
					}
				</style>
			</div>
		</div>
	</div>
</div>