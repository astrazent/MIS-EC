<?php
// Kiểm tra nếu tồn tại tiêu đề "Sản phẩm mới" hoặc tương tự
$new_product_title = isset($new_product_title) ? $new_product_title : 'Sản phẩm mới';
?>

<script>
	gtag('config', 'G-QPG3ZQV73K', {
		'page_title': 'Trang chủ',
		'page_path': '/'
	});
</script>

<style>
    .product-list-title {
        margin-bottom: 30px;
        text-align: center;
    }

    .product-list-title span {
        font-size: 28px;
        font-weight: 600;
        position: relative;
        padding-bottom: 10px;
        display: inline-block;
    }

    .product-list-title span:after {
        content: '';
        position: absolute;
        width: 60px;
        height: 3px;
        background-color: #4CAF50;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
    }

    /* Slider styles */
    .hero-slider {
        position: relative;
        height: 500px;
        overflow: hidden;
    }

    .hero-slide {
        width: 100%;
        height: 500px;
        background-size: cover;
        background-position: center;
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }

    .hero-slide.active {
        display: block;
    }

    .hero-content {
        position: relative;
        text-align: left;
        color: #fff;
        max-width: 600px;
        padding: 100px 0;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
    }

    .hero-content h1 {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .hero-content p {
        font-size: 18px;
        margin-bottom: 30px;
    }

    .slider-navigation {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
        z-index: 100;
    }

    .slider-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.5);
        cursor: pointer;
    }

    .slider-dot.active {
        background-color: #fff;
    }

    .slider-control {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.3);
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        font-size: 18px;
        cursor: pointer;
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .slider-prev {
        left: 20px;
    }

    .slider-next {
        right: 20px;
    }
</style>
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-slider">
        <?php
        $slider_dir = './upload/slider/';
        $slider_files = glob($slider_dir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        $slider_count = count($slider_files);
        
        if ($slider_count > 0) {
            foreach ($slider_files as $index => $file) {
                $relative_file = str_replace('./', '', $file);
                $active_class = ($index === 0) ? 'active' : '';
        ?>
                <div class="hero-slide <?php echo $active_class; ?>" style="background-image: url('<?php echo base_url($relative_file); ?>');">
                    <div class="container">
                        <div class="hero-content">
                            <h1>Bộ sưu tập mùa hè 2025</h1>
                            <p>Khám phá xu hướng thời trang mới nhất</p>
                            <a href="<?php echo base_url('moi'); ?>" class="btn btn-primary">Mua ngay</a>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            // Fallback if no slider images found
        ?>
            <div class="hero-slide active" style="background-image: url('<?php echo base_url('upload/slide1.png'); ?>');">
                <div class="container">
                    <div class="hero-content">
                        <h1>Bộ sưu tập mùa hè 2025</h1>
                        <p>Khám phá xu hướng thời trang mới nhất</p>
                        <a href="<?php echo base_url('moi'); ?>" class="btn btn-primary">Mua ngay</a>
                    </div>
                </div>
            </div>
        <?php } ?>
        
        <!-- Slider controls -->
        <button class="slider-control slider-prev"><i class="fas fa-chevron-left"></i></button>
        <button class="slider-control slider-next"><i class="fas fa-chevron-right"></i></button>
        
        <!-- Navigation dots -->
        <div class="slider-navigation">
            <?php for($i = 0; $i < max(1, $slider_count); $i++) { ?>
                <div class="slider-dot <?php echo ($i === 0) ? 'active' : ''; ?>" data-slide="<?php echo $i; ?>"></div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- JavaScript for slider -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentSlide = 0;
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.slider-dot');
        const totalSlides = slides.length;
        
        function showSlide(index) {
            // Hide all slides
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            // Show selected slide
            currentSlide = (index + totalSlides) % totalSlides;
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
        }
        
        // Next slide
        document.querySelector('.slider-next').addEventListener('click', function() {
            showSlide(currentSlide + 1);
        });
        
        // Previous slide
        document.querySelector('.slider-prev').addEventListener('click', function() {
            showSlide(currentSlide - 1);
        });
        
        // Dot navigation
        dots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                showSlide(index);
            });
        });
        
        // Auto slide
        setInterval(function() {
            showSlide(currentSlide + 1);
        }, 5000);
    });
</script>

<!-- New Products Section -->
<section class="products-section">
    <div class="container">
        <div class="product-list-title">
            <span><?php echo $new_product_title; ?></span>
        </div>		
        
        <div class="row">
            <?php foreach ($new_product as $row): ?>
            <?php 
                // Sử dụng slug nếu có, nếu không tạo slug từ tên
                $product_url = isset($row->slug) ? $row->slug : covert_vi_to_en($row->name);
                $product_url = strtolower($product_url);
                    ?>
            <div class="col-6 col-md-3">
                <div class="product-item">
                    <?php if(isset($row->discount) && $row->discount > 0): ?>
                    <div class="product-badge sale">Sale</div>
                    <?php else: ?>
                    <div class="product-badge new">New</div>
                    <?php endif; ?>
                    
                    <div class="product-item-image">
                        <a href="<?php echo base_url($product_url.'-p'.$row->id); ?>">
                            <img src="<?php echo base_url('upload/product/'.$row->image_link); ?>" alt="<?php echo $row->name; ?>" class="img-fluid">
                                    </a>
                    </div>
                    
                    <div class="product-item-content">
                        <h3 class="product-item-title">
                            <a href="<?php echo base_url($product_url.'-p'.$row->id); ?>"><?php echo $row->name; ?></a>
                        </h3>
                        
                        <div class="product-price">
							<?php if ($row->discount > 0 || $row->price < $row->origin_price): 
								$new_price = $row->price - $row->discount; 
							?>
								<span class="current-price"><?php echo number_format($new_price); ?> VNĐ</span>
								<span class="old-price"><?php echo number_format($row->price); ?> VNĐ</span>
							<?php else: ?>
								<span class="current-price"><?php echo number_format($row->origin_price); ?> VNĐ</span>
							<?php endif; ?>
						</div>
                        
                        <div class="product-item-actions">
                            <a href="<?php echo base_url('cart/add/'.$row->id); ?>" class="btn btn-primary add-to-cart-btn">
                                <i class="fas fa-shopping-bag"></i> Thêm giỏ hàng
                                    </a>
                                </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Best Sellers Section -->
<section class="products-section">
    <div class="container">
        <div class="product-list-title">
            <span>Sản phẩm bán chạy</span>
        </div>
        
        <div class="row">
            <?php foreach ($hot_product as $row): ?>
            <?php 
                // Sử dụng slug nếu có, nếu không tạo slug từ tên
                $product_url = isset($row->slug) ? $row->slug : covert_vi_to_en($row->name);
                $product_url = strtolower($product_url);
            ?>
            <div class="col-6 col-md-3">
                <div class="product-item">
                    <div class="product-badge bestseller">Best Seller</div>
                    
                    <div class="product-item-image">
                        <a href="<?php echo base_url($product_url.'-p'.$row->id); ?>">
                            <img src="<?php echo base_url('upload/product/'.$row->image_link); ?>" alt="<?php echo $row->name; ?>" class="img-fluid">
                                </a>
                            </div>
                    
                    <div class="product-item-content">
                        <h3 class="product-item-title">
                            <a href="<?php echo base_url($product_url.'-p'.$row->id); ?>"><?php echo $row->name; ?></a>
                        </h3>
                        
                        <div class="product-price">
							<?php if ($row->discount > 0 || $row->price < $row->origin_price): 
								$new_price = $row->price - $row->discount; 
							?>
								<span class="current-price"><?php echo number_format($new_price); ?> VNĐ</span>
								<span class="old-price"><?php echo number_format($row->price); ?> VNĐ</span>
							<?php else: ?>
								<span class="current-price"><?php echo number_format($row->origin_price); ?> VNĐ</span>
							<?php endif; ?>
						</div>
                        
                        <div class="product-item-actions">
                            <a href="<?php echo base_url('cart/add/'.$row->id); ?>" class="btn btn-primary add-to-cart-btn">
                                <i class="fas fa-shopping-bag"></i> Thêm giỏ hàng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Most Viewed Products Section -->
<section class="products-section">
    <div class="container">
        <div class="product-list-title">
            <span>Sản phẩm xem nhiều</span>
        </div>
        
        <div class="row">
            <?php foreach ($view_product as $row): ?>
            <?php 
                // Sử dụng slug nếu có, nếu không tạo slug từ tên
                $product_url = isset($row->slug) ? $row->slug : covert_vi_to_en($row->name);
                $product_url = strtolower($product_url);
            ?>
            <div class="col-6 col-md-3">
                <div class="product-item">
                    <div class="product-badge trending">Trending</div>
                    
                    <div class="product-item-image">
                        <a href="<?php echo base_url($product_url.'-p'.$row->id); ?>">
                            <img src="<?php echo base_url('upload/product/'.$row->image_link); ?>" alt="<?php echo $row->name; ?>" class="img-fluid">
                        </a>
</div>
                    
                    <div class="product-item-content">
                        <h3 class="product-item-title">
                            <a href="<?php echo base_url($product_url.'-p'.$row->id); ?>"><?php echo $row->name; ?></a>
                        </h3>
                        
                        <div class="product-price">
							<?php if ($row->discount > 0 || $row->price < $row->origin_price): 
								$new_price = $row->price - $row->discount; 
							?>
								<span class="current-price"><?php echo number_format($new_price); ?> VNĐ</span>
								<span class="old-price"><?php echo number_format($row->price); ?> VNĐ</span>
							<?php else: ?>
								<span class="current-price"><?php echo number_format($row->origin_price); ?> VNĐ</span>
							<?php endif; ?>
						</div>
                        
                        <div class="product-item-actions">
                            <a href="<?php echo base_url('cart/add/'.$row->id); ?>" class="btn btn-primary add-to-cart-btn">
                                <i class="fas fa-shopping-bag"></i> Thêm giỏ hàng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Miễn phí vận chuyển</h4>
                        <p>Cho đơn hàng trên 500.000 VNĐ</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Đổi trả dễ dàng</h4>
                        <p>Chính sách đổi trả 30 ngày</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Thanh toán an toàn</h4>
                        <p>Bảo mật thanh toán 100%</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Hỗ trợ khách hàng</h4>
                        <p>Dịch vụ hỗ trợ 24/7</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>