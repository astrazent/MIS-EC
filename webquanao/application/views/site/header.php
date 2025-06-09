<header class="site-header">
    <div class="container">
        <div class="header-wrapper">
            <!-- Mobile Menu Toggle -->
            <div class="mobile-toggle d-lg-none">
                <button class="navbar-toggler" type="button">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <!-- Logo -->
            <div class="site-logo">
                <a href="<?php echo base_url(); ?>">
                    <img src="<?php echo base_url(); ?>upload/download.png" alt="Ngọc Lan" class="img-fluid" style="width: 100px; height: 100px; border-radius: 50%;">
                </a>
            </div>

            <!-- Navigation -->
<!-- Navigation -->
        <nav class="site-navigation d-none d-lg-flex">
            <?php
                // Lấy segment đầu tiên của URI
                // Ví dụ: http://localhost/thoi-trang-nu-c8 -> $current_page = "thoi-trang-nu-c8"
                // Nếu là trang chủ, segment sẽ rỗng.
                $current_page = $this->uri->segment(1);
            ?>
            <ul class="nav-menu">
                <!-- Trang chủ: active khi không có segment nào (trang gốc) -->
                <li class="nav-item <?php echo ($current_page == '') ? 'active' : ''; ?>">
                    <a href="<?php echo base_url(); ?>">Trang chủ</a>
                </li>

                <!-- Mới: active khi segment là 'moi' -->
                <li class="nav-item <?php echo ($current_page == 'moi') ? 'active' : ''; ?>">
                    <a href="<?php echo base_url('moi'); ?>">Mới</a>
                </li>

                <!-- Nữ: active khi segment là 'thoi-trang-nu-c8' -->
                <li class="nav-item <?php echo ($current_page == 'thoi-trang-nu-c8') ? 'active' : ''; ?>">
                    <a href="<?php echo base_url('thoi-trang-nu-c8'); ?>">Nữ</a>
                </li>

                <!-- Nam: active khi segment là 'thoi-trang-nam-c7' -->
                <li class="nav-item <?php echo ($current_page == 'thoi-trang-nam-c7') ? 'active' : ''; ?>">
                    <a href="<?php echo base_url('thoi-trang-nam-c7'); ?>">Nam</a>
                </li>

                <!-- Gia đình: active khi segment là 'quan-ao-gia-dinh-c9' -->
                <li class="nav-item <?php echo ($current_page == 'quan-ao-gia-dinh-c9') ? 'active' : ''; ?>">
                    <a href="<?php echo base_url('quan-ao-gia-dinh-c9'); ?>">Gia đình</a>
                </li>

                <!-- Khuyến mãi: active khi segment là 'khuyen-mai' -->
                <li class="nav-item <?php echo ($current_page == 'khuyen-mai') ? 'active' : ''; ?>">
                    <a href="<?php echo base_url('khuyen-mai'); ?>">Khuyến mãi</a>
                </li>
            </ul>
        </nav>

            <!-- Header Actions -->
            <div class="header-actions">
                <div class="search-toggle">
                    <button type="button" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="image-search">
                    <label for="image-upload" class="image-search-btn" id="image-search-button">
                        <i class="fas fa-camera"></i>
                    </label>
                    <input type="file" id="image-upload" name="image" accept="image/*" style="display: none;">
                </div>
                <div class="account-link">
                    <?php if (!isset($user)) { ?>
                        <a href="<?php echo base_url('dang-nhap'); ?>">
                            <i class="fas fa-user"></i>
                        </a>
                    <?php } else { ?>
                        <div class="user-dropdown">
                            <div class="user-toggle">
                                <i class="fas fa-user"></i>
                                <span class="toggle-arrow">
                                    <i class="fas fa-chevron-down"></i>
                                </span>
                            </div>
                            <div class="dropdown-menu">
                                <div class="user-greeting">
                                    <i class="fas fa-smile"></i>
                                    <span>Xin chào, <?php echo isset($user_info) ? $user_info->name : $user->name; ?>!</span>
                                </div>
                                <ul>
                                    <li>
                                        <a href="<?php echo site_url('user/index'); ?>">
                                            <i class="fas fa-user-circle"></i>
                                            Thông tin tài khoản
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url('shipment'); ?>">
                                            <i class="fas fa-box"></i>
                                            Đơn hàng của tôi
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo site_url('user/logout'); ?>">
                                            <i class="fas fa-sign-out-alt"></i>
                                            Đăng xuất
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="cart-link">
                    <?php $this->load->view('site/cart/cart_sh'); ?>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    // Image upload processing
    var imageUploadElement = document.getElementById('image-upload');
    if (imageUploadElement) {
        imageUploadElement.addEventListener('change', function() {
            // Thay đổi giao diện nút thành dấu ba chấm nhấp nháy
            var searchButton = document.getElementById('image-search-button');
            searchButton.innerHTML = '<div class="loading-dots"><span>.</span><span>.</span><span>.</span></div>';

            var formData = new FormData();
            formData.append('image', this.files[0]);

            fetch('<?php echo base_url("image-search"); ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Khôi phục lại giao diện nút sau khi xử lý xong
                    searchButton.innerHTML = '<span class="glyphicon glyphicon-camera"></span>';

                    if (data.success) {
                        sessionStorage.setItem('product_list', JSON.stringify(data.product_list));
                        window.location.href = '<?php echo base_url("tim-kiem-ket-qua"); ?>';
                    } else {
                        alert(data.message || 'Tìm kiếm không thành công!');
                    }
                })
                .catch(error => {
                    // Khôi phục lại giao diện nút nếu có lỗi
                    searchButton.innerHTML = '<span class="glyphicon glyphicon-camera"></span>';

                    console.error('Lỗi:', error);
                    alert('Đã xảy ra lỗi trong quá trình tìm kiếm. Vui lòng thử lại sau!');
                });
        });
    }
</script>
