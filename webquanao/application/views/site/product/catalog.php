<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
	<ol class="breadcrumb bg-transparent p-0">
		<li class="breadcrumb-item">
			<a href="<?php echo base_url(); ?>"><i class="fas fa-home"></i> Trang chủ</a>
		</li>
		<li class="breadcrumb-item active" aria-current="page"><?php echo $catalog_p->name; ?></li>
	</ol>
</nav>

<!-- Products Section -->
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $catalog_p->name; ?></h3>
	</div>
	<div class="card-body">
		<?php if ($total > 0): ?>
			<div class="row g-3">
				<?php foreach ($product_list as $value): 
					$name = covert_vi_to_en($value->name);
					$name = strtolower($name);
				?>
					<div class="col-md-4 col-sm-6">
						<div class="product-card">
							<?php if ($value->discount > 0): ?>
								<div class="product-badge sale">-<?php echo $value->discount; ?>%</div>
							<?php endif; ?>
							
							<div class="product-image">
								<a href="<?php echo base_url('product/view/' . $value->id); ?>">
									<img src="<?php echo base_url('upload/product/' . $value->image_link); ?>" alt="<?php echo $value->name; ?>" class="img-fluid">
								</a>
								<div class="product-actions">
									<button><i class="fas fa-heart"></i></button>
									<a href="<?php echo base_url('cart/add/' . $value->id); ?>"><i class="fas fa-shopping-cart"></i></a>
									<button><i class="fas fa-eye"></i></button>
								</div>
							</div>
							
							<div class="product-content">
								<h3 class="product-title">
									<a href="<?php echo base_url('product/view/' . $value->id); ?>"><?php echo $value->name; ?></a>
								</h3>
								
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
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			
			<!-- Pagination -->
			<div class="catalog-pagination">
				<?php echo $this->pagination->create_links(); ?>
				<?php if(!$this->pagination->create_links()): ?>
					<ul class="pagination">
						<li class="page-item active"><span class="page-link">1</span></li>
					</ul>
				<?php endif; ?>
			</div>
			
		<?php else: ?>
			<div class="empty-products text-center py-5">
				<i class="fas fa-box-open fa-3x text-muted mb-3"></i>
				<p class="mb-4">Không có sản phẩm nào trong danh mục này</p>
				<a href="<?php echo base_url(); ?>" class="btn btn-primary">Về trang chủ</a>
			</div>
		<?php endif; ?>
	</div>
</div>