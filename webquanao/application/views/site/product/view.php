<script>
	gtag('config', 'G-QPG3ZQV73K', {
		'page_title': 'Trang sản phẩm: <?php echo $product->name; ?>',
		'page_path': '/<?php echo $product->name; ?>'
	});
</script>

<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 clearpaddingr">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearpadding">
		<style>
			/* Kiểu cho phần tổng hợp đánh giá AI */
			.ai-summary-container {
				padding: 15px;
				background-color: #f9f9f9;
				border-radius: 5px;
				margin-bottom: 15px;
			}
			.ai-summary-content {
				white-space: pre-line;
				line-height: 1.6;
			}
			.ai-summary-header {
				display: flex;
				align-items: center;
				margin-bottom: 10px;
			}
			.ai-summary-header i {
				margin-right: 8px;
				color: #3498db;
			}
			.ai-summary-section {
				margin-bottom: 10px;
			}
			.ai-summary-section h4 {
				font-weight: bold;
				margin-top: 15px;
				margin-bottom: 8px;
			}
			/* Hiệu ứng quay cho biểu tượng loading */
			@keyframes spin {
				0% { transform: rotate(0deg); }
				100% { transform: rotate(360deg); }
			}
			/* Kiểu cho danh sách trong tổng hợp */
			.ai-summary-content li {
				margin-bottom: 5px;
			}
			/* Fix for JQZoom */
			.zoomPup, .zoomWindow, .zoomPreload { display: block !important; }
			.zoomWrapper { position: relative; display: inline-block; }
			.zoomWrapperImage { display: block; position: relative; overflow: hidden; z-index: 110; }
			.zoomWrapperImage img { display: block; max-width: none !important; }
			.main-image { position: relative; width: 100%; min-height: 300px; }
			.main-image a { display: inline-block; }
			.main-image img { max-width: 100%; height: auto; }
			/* Fix for hover-off issue */
			.zoomPup, .zoomWindow { opacity: 0; visibility: hidden; transition: opacity 0.2s ease; }
			.jqZoomPup.visible, .zoomWindow.visible { opacity: 1; visibility: visible; }
			.img-thumbnail { width: 60px; height: 60px; object-fit: cover; } /* Cải thiện thumbnail */
		</style>
		
		<!-- SỬA 1: Đổi sang Breadcrumb hiện đại hơn -->
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><i class="fas fa-home"></i> Trang chủ</a></li>
			<li class="breadcrumb-item"><a href="<?php echo base_url('product/catalog/' . $catalog_product->id); ?>"><?php echo $catalog_product->name; ?></a></li>
			<li class="breadcrumb-item active" aria-current="page"><?php echo $product->name; ?></li>
		</ol>

		<!-- SỬA 2: Bổ sung các file JS còn thiếu để sửa lỗi -->
		<!-- 1. Tải thư viện jQuery chính -->
		<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

		<!-- 2. Tải jQuery Migrate (để vá lỗi cho jqZoom cũ) -->
		<script src="https://code.jquery.com/jquery-migrate-1.4.1.min.js"></script>
		
		<!-- 3. Tải plugin Raty (cho đánh giá sao) -->
		<script src="<?php echo public_url('js/raty/jquery.raty.js'); ?>"></script> <!-- Đảm bảo đường dẫn này đúng -->

		<!-- 4. Tải plugin jqZoom -->
		<script src="<?php echo public_url('js'); ?>/jqzoom_ev/js/jquery.jqzoom-core.js" type="text/javascript"></script>
		<link rel="stylesheet" href="<?php echo public_url('js'); ?>/jqzoom_ev/css/jquery.jqzoom.css" type="text/css">
		
		<script type="text/javascript">
			$(document).ready(function() {
				// Khởi tạo JqZoom
				var zoomOptions = {
					zoomType: 'standard', lens: true, preloadImages: true, alwaysOn: false,
					zoomWidth: 400, zoomHeight: 400, xOffset: 10, yOffset: 0,
					position: 'right', title: false, hideEffect: 'fadeout', fadeoutSpeed: 200
				};
				$('.jqzoom').jqzoom(zoomOptions);

				$('.jqzoom').on('mouseenter', function() {
					setTimeout(function() { $('.zoomPup, .zoomWindow').addClass('visible'); }, 100);
				});
				$('.jqzoom').on('mouseleave', function() {
					$('.zoomPup, .zoomWindow').removeClass('visible');
				});

				// Khởi tạo Raty (đánh giá sao)
				$('.raty_detailt').raty({
					score: function() { return $(this).attr('data-score'); },
					half: true,
					click: function(score, evt) {
						var rate_count = $('.rate_count');
						var rate_count_total = rate_count.text();
						$.ajax({
							url: '<?php echo base_url('product/raty'); ?>',
							type: 'POST',
							data: { 'id': '<?php echo $product->id; ?>', 'score': score },
							dataType: 'json',
							success: function(data) {
								if (data.complete) {
									var total = parseInt(rate_count_total) + 1;
									rate_count.html(parseInt(total));
								}
								alert(data.msg);
							}
						});
					}
				});
			});
		</script>
		
		<!-- SỬA 3: Sử dụng cấu trúc HTML hiện đại của bạn bè để hiển thị chi tiết sản phẩm -->
		<div class="col-12">
			<div class="card product-detail-card mb-5">
				<div class="card-body p-4">
					<div class="row">
						<!-- Product Images -->
						<div class="col-md-6 mb-4">
							<div class="product-images">
								<!-- Main Image with Zoom -->
								<div class="main-image mb-3 text-center">
									<a href="<?php echo base_url('upload/product/' . $product->image_link); ?>" class="jqzoom" rel="gal1" title="<?php echo $product->name; ?>">
										<img src="<?php echo base_url('upload/product/' . $product->image_link); ?>" alt="<?php echo $product->name; ?>" class="product-main-img img-fluid">
									</a>
								</div>
								
								<!-- Thumbnail Gallery -->
								<div class="product-thumbnails">
									<ul id="thumblist" class="d-flex flex-wrap list-unstyled">
										<li class="thumbnail-item me-2 mb-2">
											<a class="zoomThumbActive" href='javascript:void(0);' rel="{gallery: 'gal1', smallimage: '<?php echo base_url("upload/product/" . $product->image_link); ?>',largeimage: '<?php echo base_url("upload/product/" . $product->image_link); ?>'}">
												<img src='<?php echo base_url("upload/product/" . $product->image_link); ?>' alt="thumbnail" class="img-thumbnail">
											</a>
										</li>
										<?php if (is_array($image_list)): ?>
											<?php foreach ($image_list as $value) { ?>
												<li class="thumbnail-item me-2 mb-2">
													<a href='javascript:void(0);' rel="{gallery: 'gal1', smallimage: '<?php echo base_url("upload/product/" . $value); ?>',largeimage: '<?php echo base_url("upload/product/" . $value); ?>'}">
														<img src='<?php echo base_url("upload/product/" . $value); ?>' alt="thumbnail" class="img-thumbnail">
													</a>
												</li>
											<?php } ?>
										<?php endif; ?>
									</ul>
								</div>
							</div>
						</div>
						
						<!-- Product Info -->
						<div class="col-md-6">
							<h1 class="product-title mb-3"><?php echo $product->name; ?></h1>
							
							<div class="product-rating mb-3">
								<span class='raty_detailt d-inline-block me-2' id='<?php echo $product->id; ?>' data-score='<?php echo round(($product->rate_count > 0) ? ($product->rate_total / $product->rate_count) : 0, 2); ?>'></span>
								<span class="rating-count">| <b class='rate_count'><?php echo $product->rate_count; ?></b> đánh giá</span>
							</div>
							
							<div class="product-description mb-4">
								<p><?php echo $product->content; ?></p>
							</div>
							
							<!-- SỬA 4: Sửa lỗi hiển thị giá khuyến mãi -->
							<div class="product-price mb-4">
								<?php if ($product->discount > 0 || $product->price < $product->origin_price): ?>
									<?php $price_new = $product->price - $product->discount; ?>
									<div class="old-price mb-1">Giá gốc: <del><?php echo number_format($product->origin_price) ?> VNĐ</del></div>
									<div class="current-price">Giá khuyến mãi: <span class="price-value" style="font-weight: bold;color: green"><?php echo number_format($price_new); ?> VNĐ</span></div>
								<?php else: ?>
									<div class="current-price">Giá: <span class="price-value" style="font-weight: bold;color: green"><?php echo number_format($product->origin_price); ?> VNĐ</span></div>
								<?php endif; ?>
							</div>
							
							<div class="product-meta mb-4">
								<div class="meta-item mb-2">
									<i class="fas fa-eye me-2"></i> Lượt xem: <span><?php echo $product->view; ?></span>
								</div>
								<div class="meta-item mb-2">
									<i class="fas fa-shopping-bag me-2"></i> Đã bán: <span><?php echo $product->buyed; ?></span>
								</div>
							</div>
							<div class="product-actions">
								<a href="<?php echo base_url('cart/add/' . $product->id); ?>" class="btn btn-primary btn-lg">
									<i class="fas fa-shopping-cart me-2"></i> Thêm vào giỏ hàng
								</a>
							</div>
						</div>
					</div>
					
					<!-- Product Services Banner -->
					<div class="row mt-5">
						<div class="col-md-4 text-center mb-3">
							<div class="service-item">
								<img src="<?php echo base_url(); ?>upload/icon/services.png" alt="Phục vụ chu đáo" class="service-icon mb-2">
								<p class="service-text">Phục vụ chu đáo</p>
							</div>
						</div>
						<div class="col-md-4 text-center mb-3">
							<div class="service-item">
								<img src="<?php echo base_url(); ?>upload/icon/ship.png" alt="Trao hàng đúng hẹn" class="service-icon mb-2">
								<p class="service-text">Trao hàng đúng hẹn</p>
							</div>
						</div>
						<div class="col-md-4 text-center mb-3">
							<div class="service-item">
								<img src="<?php echo base_url(); ?>upload/icon/services.png" alt="Đổi hàng trong 24h" class="service-icon mb-2">
								<p class="service-text">Đổi hàng trong 24h</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<!-- SỬA 5: Thêm điều kiện chỉ hiện box AI khi có bình luận -->
			<?php if (!empty($comments)): ?>
				<div class="panel panel-info" style="margin-bottom: 15px">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="glyphicon glyphicon-stats"></i> Tổng hợp đánh giá bằng AI</h3>
					</div>
					<div class="panel-body">
						<div id="ai-summary-container" style="display: none;">
							<div id="ai-summary-loading" style="display: none;" class="text-center">
								<p><i class="glyphicon glyphicon-refresh" style="animation: spin 2s linear infinite;"></i> Đang tổng hợp đánh giá...</p>
								<p class="text-muted">Quá trình này có thể mất vài giây, vui lòng đợi...</p>
							</div>
							<div id="ai-summary-content" style="display: none;" class="ai-summary-container">
								<div class="ai-summary-header">
									<i class="glyphicon glyphicon-check"></i>
									<h4>Phân tích đánh giá từ khách hàng</h4>
								</div>
								<div class="ai-summary-content"></div>
							</div>
							<div id="ai-summary-error" style="display: none;" class="alert alert-danger">
								<i class="glyphicon glyphicon-exclamation-sign"></i> Không thể tổng hợp đánh giá. <span id="ai-summary-error-message"></span>
							</div>
							<div id="ai-summary-empty" style="display: none;" class="alert alert-info">
								<i class="glyphicon glyphicon-info-sign"></i> Chưa đủ đánh giá để tổng hợp.
							</div>
						</div>
						<div class="text-center">
							<button id="generate-ai-summary" class="btn btn-primary">
								<i class="glyphicon glyphicon-flash"></i> Tổng hợp đánh giá bằng AI
							</button>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<!-- SỬA 6: Cập nhật khu vực bình luận theo mẫu hiện đại -->
			<div class="card mb-5">
				<div class="card-header bg-light">
					<h3 class="card-title mb-0">Bình luận</h3>
				</div>
				<div class="card-body comment-panel-body">
					<?php if (!empty($comments)): ?>
						<ul class="comment-list list-unstyled" id="comment-list">
							<?php foreach ($comments as $comment): ?>
								<li class="comment-item mb-4 pb-3 border-bottom">
									<div class="product-rating">
										<div class="d-flex">
											<div class="product-rating__avatar me-3">
												<div class="avatar">
													<img src="<?php echo base_url('upload/avatar/default-avatar.jpg'); ?>" 
														alt="Avatar" class="rounded-circle" width="40" height="40">
												</div>
											</div>
											<div class="product-rating__main">
												<div class="product-rating__author-name fw-bold mb-1">
													<?php echo $comment->user_name; ?>
												</div>
												<div class="product-rating__rating mb-2">
													<?php for ($i = 1; $i <= 5; $i++): ?>
														<i class="fa<?php echo ($i <= $comment->rate) ? 's' : 'r'; ?> fa-star text-warning"></i>
													<?php endfor; ?>
												</div>
												<div class="product-rating__content">
													<?php echo htmlspecialchars($comment->comment_content); ?>
												</div>
											</div>
										</div>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php else: ?>
						<div class="text-center py-4">
							<i class="fas fa-comments fa-3x text-secondary mb-3"></i>
							<p class="text-muted">Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>
						</div>
					<?php endif; ?>
				</div>
			</div>
		
		<!-- SỬA 7: Đồng bộ hóa giao diện sản phẩm liên quan/bạn cũng thích -->
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">Sản phẩm liên quan</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<?php foreach ($productsub as $value) {
						$name = covert_vi_to_en($value->name);
						$name = strtolower($name);
					?>
						<div class="col-md-3 col-sm-6 col-xs-6 product-column mb-4">
							<div class="product-item card h-100">
								<a href="<?php echo base_url($name . '-p' . $value->id); ?>">
									<img src="<?php echo base_url('upload/product/' . $value->image_link); ?>" alt="<?php echo $value->name; ?>" class="card-img-top">
								</a>
								<div class="card-body d-flex flex-column">
									<h5 class="product-item-title card-title">
										<a href="<?php echo base_url($name . '-p' . $value->id); ?>"><?php echo $value->name; ?></a>
									</h5>
									<div class="product-item-price mt-auto">
										<?php if ($value->discount > 0 || $value->price < $value->origin_price) {
											$new_price = $value->price - $value->discount; ?>
											<span class="price-value"><?php echo number_format($new_price); ?> VNĐ</span>
											<del class="old-price"><?php echo number_format($value->origin_price); ?> VNĐ</del>
										<?php } else { ?>
											<span class="price-value"><?php echo number_format($value->origin_price); ?> VNĐ</span>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">Có thể bạn cũng thích</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<?php foreach ($productview as $value) {
						$name = covert_vi_to_en($value->name);
						$name = strtolower($name);
					?>
						<div class="col-md-3 col-sm-6 col-xs-6 product-column mb-4">
							<div class="product-item card h-100">
								<a href="<?php echo base_url($name . '-p' . $value->id); ?>">
									<img src="<?php echo base_url('upload/product/' . $value->image_link); ?>" alt="<?php echo $value->name; ?>" class="card-img-top">
								</a>
								<div class="card-body d-flex flex-column">
									<h5 class="product-item-title card-title">
										<a href="<?php echo base_url($name . '-p' . $value->id); ?>"><?php echo $value->name; ?></a>
									</h5>
									<div class="product-item-price mt-auto">
										<?php if ($value->discount > 0 || $value->price < $value->origin_price) {
											$new_price = $value->price - $value->discount; ?>
											<span class="price-value"><?php echo number_format($new_price); ?> VNĐ</span>
											<del class="old-price"><?php echo number_format($value->origin_price); ?> VNĐ</del>
										<?php } else { ?>
											<span class="price-value"><?php echo number_format($value->origin_price); ?> VNĐ</span>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- SỬA 8: Đặt script xử lý AI ở cuối cùng -->
<script>
    $(document).ready(function () {
		$('#generate-ai-summary').on('click', function() {
			const productId = <?php echo $product->id; ?>;
			
			$('#ai-summary-container').show();
			$('#ai-summary-loading').show();
			$('#ai-summary-content, #ai-summary-error, #ai-summary-empty').hide();
			$(this).prop('disabled', true);
			
			$.ajax({
				url: '<?php echo base_url('product/get_review_summary'); ?>',
				type: 'POST',
				data: { product_id: productId },
				dataType: 'json',
				success: function(response) {
					$('#ai-summary-loading').hide();
					if (response.success) {
						$('#ai-summary-content .ai-summary-content').html(formatSummary(response.summary));
						$('#ai-summary-content').show();
						$('#generate-ai-summary').closest('.text-center').hide(); // Ẩn luôn cả nút và div bao nó
					} else {
						if (response.message.includes('chưa có bình luận')) {
							$('#ai-summary-empty').show();
						} else {
							$('#ai-summary-error-message').text(response.message);
							$('#ai-summary-error').show();
						}
						$('#generate-ai-summary').prop('disabled', false);
					}
				},
				error: function(xhr) {
					$('#ai-summary-loading').hide();
					$('#ai-summary-error-message').text('Đã xảy ra lỗi khi kết nối đến máy chủ.');
					$('#ai-summary-error').show();
					$('#generate-ai-summary').prop('disabled', false);
					console.error('Error:', xhr.responseText);
				}
			});
		});
        
        function formatSummary(summary) {
            let formattedSummary = summary;
            const headings = [ 'Tóm tắt', 'Điểm mạnh', 'Điểm yếu', 'Đánh giá tổng thể', 'Đề xuất', 'Kết luận', 'Ưu điểm', 'Nhược điểm', 'Đề xuất cải tiến' ];
            
            headings.forEach(heading => {
                const regex = new RegExp(`(^|\\n)(${heading}:)`, 'gi');
                formattedSummary = formattedSummary.replace(regex, '$1<div class="ai-summary-section"><h4>$2</h4>');
            });
            
            formattedSummary = formattedSummary.replace(/\n(\d+\.|\*|\-)/g, '</div>\n$1');
            formattedSummary = formattedSummary.replace(/(^|\n)(\d+\.|\*|\-)(.*)/g, '$1<li>$3</li>');
            
            // Xử lý các dòng không phải list item bên trong section
            formattedSummary = formattedSummary.replace(/<\/h4>\n([^<])/g, '</h4><p>$1');
            formattedSummary = formattedSummary.replace(/([^\/])\n<div/g, '$1</p>\n<div');

            if (formattedSummary.includes('<div class="ai-summary-section">') && !formattedSummary.endsWith('</div>')) {
                formattedSummary += '</div>';
            }
            
            return formattedSummary.replace(/\n/g, '<br>');
        }
    });
</script>