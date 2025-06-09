<?php
$catalog_id = isset($catalog_id) ? $catalog_id : NULL;
$price_from = isset($price_from) ? $price_from : NULL;
$price_to = isset($price_to) ? $price_to : NULL;
?>
<div class="search-sidebar">
	<!-- Search Filter Card -->
	<div class="card sidebar-card mb-4">
		<div class="card-header">
			<h5 class="card-title mb-0">Tìm kiếm</h5>
		</div>
		<div class="card-body">
			<form class="search-filter-form" method="post" action="<?php echo base_url('product/search'); ?>">
				<!-- Category Selection -->
				<div class="filter-section mb-3">
					<label class="filter-label">Danh mục</label>
					<div class="filter-field">
						<select class="form-select" name="catalog_id">
							<?php foreach ($catalog as $value) { ?>
								<option value="<?php echo $value->id; ?>" class="fw-bold" <?php if ($value->id == $catalog_id) echo 'selected'; ?>><?php echo $value->name; ?></option>
								<?php foreach ($value->sub as $val) { ?>
									<option value="<?php echo $val->id; ?>" <?php if ($val->id == $catalog_id) echo 'selected'; ?>>&nbsp;&nbsp;&nbsp;<?php echo $val->name; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>
				</div>

				<!-- Price Range -->
				<div class="filter-section mb-3">
					<label class="filter-label">Khoảng giá</label>
					<div class="filter-price-range">
						<div class="price-from mb-2">
							<select class="form-select" name="price_from">
								<?php for ($i = 0; $i < 1400000; $i = $i + 100000) { ?>
									<option value="<?php echo $i ?>" <?php if ($i == $price_from) echo 'selected'; ?>><?php echo number_format($i); ?> VNĐ</option>
								<?php } ?>
							</select>
						</div>
						<div class="price-separator mb-2">đến</div>
						<div class="price-to">
							<select class="form-select" name="price_to">
								<?php for ($i = 100000; $i < 1500000; $i = $i + 100000) { ?>
									<option value="<?php echo $i ?>" <?php if ($i == $price_to) echo 'selected'; ?>><?php echo number_format($i); ?> VNĐ</option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>

				<!-- Search Button -->
				<div class="search-button-container">
					<button class="btn btn-primary w-100" type="submit" name="submit">
						<i class="fas fa-search me-2"></i>Tìm kiếm
					</button>
				</div>
			</form>
		</div>
	</div>

	<!-- Categories Card -->
	<div class="card sidebar-card mb-4">
		<div class="card-header">
			<h5 class="card-title mb-0">Danh mục sản phẩm</h5>
		</div>
		<div class="card-body p-0">
			<div class="category-list">
				<?php foreach ($catalog as $value) {
					$name = covert_vi_to_en($value->name);
					$name = strtolower($name);
				?>
					<div class="category-group">
						<a href="<?php echo base_url($name . '-c' . $value->id); ?>" class="category-main-item">
							<?php echo $value->name; ?>
						</a>
						<div class="subcategory-list">
							<?php foreach ($value->sub as $val) {
								$namesub = covert_vi_to_en($val->name);
								$namesub = strtolower($namesub);
							?>
								<a href="<?php echo base_url($namesub . '-c' . $val->id); ?>" class="subcategory-item">
									<?php echo $val->name; ?>
								</a>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<style>
	/* Search sidebar styling */
	.search-sidebar {
		width: 100%;
	}
	
	/* Card styling */
	.sidebar-card {
		border-radius: 8px;
		overflow: hidden;
		box-shadow: 0 2px 8px rgba(0,0,0,0.08);
		border: none;
		margin-bottom: 20px;
		background-color: #fff;
	}

	.sidebar-card .card-header {
		padding: 12px 15px;
		background-color: #f8f9fa;
		border-bottom: 1px solid rgba(0,0,0,0.08);
	}

	.card-title {
		font-weight: 600;
		color: #333;
		font-size: 16px;
		margin: 0;
	}

	/* Filter section styling */
	.filter-section {
		margin-bottom: 15px;
	}

	.filter-label {
		display: block;
		font-weight: 600;
		margin-bottom: 8px;
		color: #333;
	}

	.filter-field {
		width: 100%;
	}

	.filter-price-range {
		width: 100%;
	}

	.price-separator {
		text-align: center;
		font-weight: 500;
		color: #666;
	}

	/* Form elements styling */
	.form-select {
		width: 100%;
		padding: 8px 12px;
		border: 1px solid #ddd;
		border-radius: 5px;
		background-color: #fff;
		font-size: 14px;
		color: #333;
		cursor: pointer;
		transition: all 0.3s ease;
	}

	.form-select:hover {
		border-color: #222;
		box-shadow: 0 0 8px rgba(0, 0, 0, 0.15);
		transform: translateY(-2px);
	}

	.form-select:focus {
		border-color: #222;
		outline: none;
		box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.15);
	}

	.search-button-container {
		width: 100%;
	}

	.btn-primary {
		background-color: #222;
		border-color: #222;
		color: #fff;
		padding: 10px 15px;
		font-weight: 500;
		transition: all 0.3s ease;
		position: relative;
		overflow: hidden;
		z-index: 1;
		border-radius: 5px;
	}

	.btn-primary::after {
		content: '';
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: #fff;
		transform: scaleY(0);
		transform-origin: bottom center;
		transition: transform 0.3s ease;
		z-index: -1;
	}

	.btn-primary:hover {
		background-color: #fff;
		border-color: #222;
		color: #222;
		transform: translateY(-3px);
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	}

	.btn-primary:hover::after {
		transform: scaleY(1);
	}

	.btn-primary:active {
		transform: translateY(-1px);
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
	}

	.btn-primary i {
		margin-right: 5px;
		transition: transform 0.3s ease;
	}

	.btn-primary:hover i {
		transform: translateX(-3px);
		color: #222;
	}

	/* Category styling */
	.category-list {
		border-radius: 0 0 8px 8px;
		overflow: hidden;
	}

	.category-group {
		border-bottom: 1px solid #eee;
	}

	.category-group:last-child {
		border-bottom: none;
	}

	.category-main-item {
		display: block;
		padding: 12px 15px;
		font-weight: 600;
		background-color: #f8f9fa;
		color: #333;
		text-decoration: none;
		transition: all 0.3s ease;
		position: relative;
		overflow: hidden;
		z-index: 1;
	}

	.category-main-item::after {
		content: '';
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: #222;
		transform: scaleX(0);
		transform-origin: right center;
		transition: transform 0.3s ease;
		z-index: -1;
	}

	.category-main-item:hover {
		color: white !important;
		transform: translateX(5px);
	}

	.category-main-item:hover::after {
		transform: scaleX(1);
		transform-origin: left center;
	}

	.subcategory-list {
		border-top: 1px solid rgba(0,0,0,0.05);
		background-color: white;
	}

	.subcategory-item {
		display: block;
		padding: 8px 15px 8px 25px;
		color: #555;
		font-size: 14px;
		border-bottom: 1px solid rgba(0,0,0,0.03);
		text-decoration: none;
		transition: all 0.3s ease;
		position: relative;
	}

	.subcategory-item:hover {
		background-color: #222 !important;
		color: white !important;
		transform: translateX(5px);
	}

	.subcategory-item::before {
		content: '';
		position: absolute;
		left: 15px;
		top: 50%;
		transform: translateY(-50%);
		width: 5px;
		height: 5px;
		background-color: transparent;
		border-radius: 50%;
		transition: all 0.3s ease;
	}

	.subcategory-item:hover::before {
		background-color: white !important;
	}

	.subcategory-item:last-child {
		border-bottom: none;
	}
</style>