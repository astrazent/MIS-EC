<!-- Breadcrumb -->
<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 clearpaddingr">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearpadding">

	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="<?php echo base_url(); ?>"><i class="fas fa-home"></i> Trang chủ</a>
		</li>
		<li class="breadcrumb-item active" aria-current="page">Tìm kiếm sản phẩm</li>
	</ol>

<!-- Search Results Section -->
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">Kết quả tìm kiếm (<?php echo $total; ?> sản phẩm)</h3>
	</div>
	
	<div class="panel-body">
		<?php if ($total > 0): ?>
			<div class="row">
				<?php foreach ($product_list as $value): 
					$name = covert_vi_to_en($value->name);
					$name = strtolower($name);
				?>
					<div class="col-md-3 col-sm-6 col-xs-6 product-column">
						<div class="product-item">
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
									<?php if (isset($value->similarity)): ?>
									<!-- <div class="similarity-score mb-2">
										<div class="progress">
											<div class="progress-bar" role="progressbar" style="width: <?php echo $value->similarity; ?>%;" aria-valuenow="<?php echo $value->similarity; ?>" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
										<small class="text-muted">Độ tương đồng: <?php echo number_format($value->similarity, 2); ?>%</small>
									</div> -->
								<?php endif; ?>
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
							<!-- <?php if ($value->discount > 0): ?>
								<div class="product-badge sale">-<?php echo $value->discount; ?>%</div>
							<?php endif; ?>
							<p class="product_name"><a href="<?php echo base_url($name . '-p' . $value->id); ?>"><?php echo $value->name; ?></a></p>
							<div class="product-image">
								<a href="<?php echo base_url($name . '-p' . $value->id); ?>"><img src="<?php echo base_url(); ?>upload/product/<?php echo $value->image_link; ?>" alt="" class=""></a>
							</div>
							<p><span class="glyphicon glyphicon-eye-open" aria-hidden="true" title="Số lượt xem"></span> <?php echo $value->view; ?> <span class="glyphicon glyphicon-star-empty" aria-hidden="true" title="Số lượng đặt mua"><?php echo $value->buyed; ?></p>
															<a href="<?php echo base_url('cart/add/' . $value->id); ?>"><button class='btn btn-info'><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Thêm giỏ hàng</button></a>
							<div class="product-content">
								<h3 class="product-title">
									<a href="<?php echo base_url('product/view/' . $value->id); ?>"><?php echo $value->name; ?></a>
								</h3>
								
								 Similarity score for image search if available 
								<?php if (isset($value->similarity)): ?>
									<div class="similarity-score mb-2">
										<div class="progress">
											<div class="progress-bar" role="progressbar" style="width: <?php echo $value->similarity; ?>%;" aria-valuenow="<?php echo $value->similarity; ?>" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
										<small class="text-muted">Độ tương đồng: <?php echo number_format($value->similarity, 2); ?>%</small>
									</div>
								<?php endif; ?>
								
								<div class="product-price">
									<?php if ($value->discount > 0 || $value->price < $value->origin_price): 
										$new_price = $value->price - $value->discount; 
									?>
										<span class="current-price"><?php echo number_format($new_price); ?> VNĐ</span>
										<span class="old-price"><?php echo number_format($value->price); ?> VNĐ</span>
									<?php else: ?>
										<span class="current-price"><?php echo number_format($value->origin_price); ?> VNĐ</span>
									<?php endif; ?>
								</div>
								
								<div class="product-meta">
									<span><i class="fas fa-eye"></i> <?php echo $value->view; ?></span>
									<span><i class="fas fa-shopping-bag"></i> <?php echo $value->buyed; ?></span>
								</div>
							</div> -->
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			
			<!-- Pagination -->
			<?php if (isset($pagination)): ?>
			<div class="pagination-container mt-4">
				<?php echo $pagination; ?>
			</div>
			<?php endif; ?>
			
		<?php else: ?>
			<div class="empty-search text-center py-5">
				<i class="fas fa-search fa-3x text-muted mb-3"></i>
				<h4 class="mb-3">Không tìm thấy sản phẩm nào</h4>
				<p class="mb-4">Vui lòng thử lại với từ khóa khác hoặc điều chỉnh bộ lọc tìm kiếm của bạn</p>
				<a href="<?php echo base_url(); ?>" class="btn btn-primary">Về trang chủ</a>
			</div>
		<?php endif; ?>
	</div>
</div>
</div>
</div>
<script>
var productList = sessionStorage.getItem('product_list');
if (productList) {
	console.log(JSON.parse(productList));
	sessionStorage.removeItem('product_list');
}
</script>