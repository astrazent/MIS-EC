
<div class="row">
			<ol class="breadcrumb">
				<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Quản trị</li>
			</ol>
		</div><!--/.row-->

		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Trang quản trị</h1>
			</div>
		</div><!--/.row-->

		<!-- Nút Phân tích AI và kết quả -->
		<div class="row">
			<div class="col-lg-12">
				<button id="btnAnalyzeAI-1" class="btn-analyze-ai btn btn-primary btn-lg" style="margin-bottom: 20px;">
					<i class="fa fa-bar-chart"></i> Phân tích AI
				</button>

				<!-- Ô hiển thị kết quả phân tích -->
				<div class="panel panel-default analysis-result-panel" style="display: none;">
					<div class="panel-heading">
						<h3 class="panel-title">Phân tích tình hình kinh doanh và đề xuất chiến lược</h3>
					</div>
					<div class="panel-body">
						<div class="loading-analysis" style="text-align: center; display: none;">
							<i class="fa fa-spinner fa-spin fa-3x"></i>
							<p>Đang phân tích dữ liệu...</p>
						</div>
						<div class="analysis-result" style="white-space: pre-line;"></div>
					</div>
				</div>
			</div>
		</div>

		
		<div class="row">
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-blue panel-widget ">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<svg class="glyph stroked bag"><use xlink:href="#stroked-bag"></use></svg>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php echo $total_order; ?></div>
							<div class="text-muted">Đơn hàng mới</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-orange panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<svg class="glyph stroked empty-message"><use xlink:href="#stroked-empty-message"></use></svg>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php echo $total_comments; ?></div>
							<div class="text-muted">Bình luận</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-teal panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php echo $new_customers; ?></div>
							<div class="text-muted">Khách hàng mới</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-red panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<svg class="glyph stroked app-window-with-content"><use xlink:href="#stroked-app-window-with-content"></use></svg>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php echo number_format($total_views); ?></div>
							<div class="text-muted">Lượt xem</div>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.row-->

		<!-- Nút Phân tích AI và kết quả -->
		<div class="row">
			<div class="col-lg-12">
				<button id="btnAnalyzeAI-1" class="btn-analyze-ai btn btn-primary btn-lg" style="margin-bottom: 20px;">
					<i class="fa fa-bar-chart"></i> Phân tích AI
				</button>

				<!-- Ô hiển thị kết quả phân tích -->
				<div class="panel panel-default analysis-result-panel" style="display: none;">
					<div class="panel-heading">
						<h3 class="panel-title">Phân tích tình hình kinh doanh và đề xuất chiến lược</h3>
					</div>
					<div class="panel-body">
						<div class="loading-analysis" style="text-align: center; display: none;">
							<i class="fa fa-spinner fa-spin fa-3x"></i>
							<p>Đang phân tích dữ liệu...</p>
						</div>
						<div class="analysis-result" style="white-space: pre-line;"></div>
					</div>
				</div>
			</div>
		</div>

		
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Site Traffic Overview</div>
					<div class="panel-body">
						<div class="canvas-wrapper">
							<canvas class="main-chart" id="line-chart" height="200" width="600"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.row-->

		<!-- Thống kê sản phẩm -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Thống kê sản phẩm</div>
					<div class="panel-body">
						<div class="row">
							<!-- 5 sản phẩm bán chạy nhất -->
							<div class="col-md-6">
								<div class="panel panel-primary">
									<div class="panel-heading">5 sản phẩm bán chạy nhất</div>
									<div class="panel-body">
										<div class="table-responsive">
											<table class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>Tên sản phẩm</th>
														<th>Giá</th>
														<th>Lượt xem</th>
														<th>Giảm giá</th>
														<th>Đã bán</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($best_selling as $product): ?>
													<tr>
														<td><?php echo $product->name; ?></td>
														<td><?php echo number_format($product->price); ?>đ</td>
														<td><?php echo $product->view; ?></td>
														<td><?php echo $product->discount_id ? number_format($product->price * $product->discount_id / 100) . 'đ' : 'Không'; ?></td>
														<td><?php echo $product->sold_count; ?></td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							
							<!-- 5 sản phẩm bán ít nhất -->
							<div class="col-md-6">
								<div class="panel panel-danger">
									<div class="panel-heading">5 sản phẩm bán ít nhất</div>
									<div class="panel-body">
										<div class="table-responsive">
											<table class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>Tên sản phẩm</th>
														<th>Giá</th>
														<th>Lượt xem</th>
														<th>Giảm giá</th>
														<th>Đã bán</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($worst_selling as $product): ?>
													<tr>
														<td><?php echo $product->name; ?></td>
														<td><?php echo number_format($product->price); ?>đ</td>
														<td><?php echo $product->view; ?></td>
														<td><?php echo $product->discount_id ? number_format($product->price * $product->discount_id / 100) . 'đ' : 'Không'; ?></td>
														<td><?php echo $product->sold_count; ?></td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<!-- 5 sản phẩm được đánh giá tốt nhất -->
							<div class="col-md-6">
								<div class="panel panel-success">
									<div class="panel-heading">5 sản phẩm được đánh giá tốt nhất</div>
									<div class="panel-body">
										<div class="table-responsive">
											<table class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>Tên sản phẩm</th>
														<th>Giá</th>
														<th>Lượt xem</th>
														<th>Giảm giá</th>
														<th>Đánh giá</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($best_rated as $product): ?>
													<tr>
														<td><?php echo $product->name; ?></td>
														<td><?php echo number_format($product->price); ?>đ</td>
														<td><?php echo $product->view; ?></td>
														<td><?php echo $product->discount_id ? number_format($product->price * $product->discount_id / 100) . 'đ' : 'Không'; ?></td>
														<td><?php echo number_format($product->avg_rating, 1); ?>/5 (<?php echo $product->rating_count; ?> đánh giá)</td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							
							<!-- 5 sản phẩm được đánh giá kém nhất -->
							<div class="col-md-6">
								<div class="panel panel-warning">
									<div class="panel-heading">5 sản phẩm được đánh giá kém nhất</div>
									<div class="panel-body">
										<div class="table-responsive">
											<table class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>Tên sản phẩm</th>
														<th>Giá</th>
														<th>Lượt xem</th>
														<th>Giảm giá</th>
														<th>Đánh giá</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($worst_rated as $product): ?>
													<tr>
														<td><?php echo $product->name; ?></td>
														<td><?php echo number_format($product->price); ?>đ</td>
														<td><?php echo $product->view; ?></td>
														<td><?php echo $product->discount_id ? number_format($product->price * $product->discount_id / 100) . 'đ' : 'Không'; ?></td>
														<td><?php echo number_format($product->avg_rating, 1); ?>/5 (<?php echo $product->rating_count; ?> đánh giá)</td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<!-- 5 sản phẩm được cho vào giỏ hàng nhiều nhất -->
							<div class="col-md-6">
								<div class="panel panel-info">
									<div class="panel-heading">5 sản phẩm được cho vào giỏ hàng nhiều nhất</div>
									<div class="panel-body">
										<div class="table-responsive">
											<table class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>Tên sản phẩm</th>
														<th>Giá</th>
														<th>Lượt xem</th>
														<th>Giảm giá</th>
														<th>Số lần thêm vào giỏ</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($most_carted as $product): ?>
													<tr>
														<td><?php echo $product->name; ?></td>
														<td><?php echo number_format($product->price); ?>đ</td>
														<td><?php echo $product->view; ?></td>
														<td><?php echo $product->discount_id ? number_format($product->price * $product->discount_id / 100) . 'đ' : 'Không'; ?></td>
														<td><?php echo $product->cart_count; ?></td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.row-->

		<!-- Nút Phân tích AI và kết quả -->
		<div class="row">
			<div class="col-lg-12">
				<button id="btnAnalyzeAI-1" class="btn-analyze-ai btn btn-primary btn-lg" style="margin-bottom: 20px;">
					<i class="fa fa-bar-chart"></i> Phân tích AI
				</button>

				<!-- Ô hiển thị kết quả phân tích -->
				<div class="panel panel-default analysis-result-panel" style="display: none;">
					<div class="panel-heading">
						<h3 class="panel-title">Phân tích tình hình kinh doanh và đề xuất chiến lược</h3>
					</div>
					<div class="panel-body">
						<div class="loading-analysis" style="text-align: center; display: none;">
							<i class="fa fa-spinner fa-spin fa-3x"></i>
							<p>Đang phân tích dữ liệu...</p>
						</div>
						<div class="analysis-result" style="white-space: pre-line;"></div>
					</div>
				</div>
			</div>
		</div>

		<!-- Đã xóa modal hiển thị kết quả phân tích -->
		
		<div class="row">
			<div class="col-xs-6 col-md-3">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>Đơn hàng mới</h4>
						<div class="easypiechart" id="easypiechart-blue" data-percent="<?php echo $order_percent; ?>" ><span class="percent"><?php echo $order_percent; ?>%</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>Bình luận mới</h4>
						<div class="easypiechart" id="easypiechart-orange" data-percent="<?php echo $comment_percent; ?>" ><span class="percent"><?php echo $comment_percent; ?>%</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>Người dùng mới</h4>
						<div class="easypiechart" id="easypiechart-teal" data-percent="<?php echo $user_percent; ?>" ><span class="percent"><?php echo $user_percent; ?>%</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>Lượt truy cập mới</h4>
						<div class="easypiechart" id="easypiechart-red" data-percent="<?php echo $visitor_percent; ?>" ><span class="percent"><?php echo $visitor_percent; ?>%</span>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.row-->
								
		