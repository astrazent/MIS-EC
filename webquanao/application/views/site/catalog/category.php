<div class="catalog-page">
    <div class="container">
        <div class="breadcrumb-area">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Trang chủ</a></li>
                <?php if(isset($catalog_parent) && $catalog_parent): ?>
                <li class="breadcrumb-item"><a href="<?php echo base_url($catalog_parent->slug); ?>"><?php echo $catalog_parent->name; ?></a></li>
                <?php endif; ?>
                <li class="breadcrumb-item active"><?php echo $catalog->name; ?></li>
            </ol>
        </div>

        <div class="catalog-header">
            <h1 class="catalog-title"><?php echo $catalog->name; ?></h1>
            <div class="catalog-description">
                <?php echo $catalog->description; ?>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="catalog-sidebar">
                    <!-- Categories -->
                    <div class="sidebar-widget">
                        <h4 class="widget-title">Danh mục</h4>
                        <ul class="category-list">
                            <?php foreach($catalog_list as $row): ?>
                            <li class="<?php echo $catalog->id == $row->id ? 'active' : ''; ?>">
                                <a href="<?php echo base_url($row->slug); ?>"><?php echo $row->name; ?></a>
                                <?php if(count($row->subs) > 0): ?>
                                <ul>
                                    <?php foreach($row->subs as $sub): ?>
                                    <li class="<?php echo $catalog->id == $sub->id ? 'active' : ''; ?>">
                                        <a href="<?php echo base_url($sub->slug); ?>"><?php echo $sub->name; ?></a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Price Filter -->
                    <div class="sidebar-widget">
                        <h4 class="widget-title">Lọc theo giá</h4>
                        <div class="price-filter">
                            <form action="<?php echo base_url($catalog->slug); ?>" method="get">
                                <div class="price-inputs">
                                    <input type="text" name="price_from" placeholder="Từ" value="<?php echo $price_from; ?>">
                                    <span>-</span>
                                    <input type="text" name="price_to" placeholder="Đến" value="<?php echo $price_to; ?>">
                                </div>
                                <button type="submit" class="btn btn-outline-primary btn-block">Lọc</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products -->
            <div class="col-lg-9">
                <div class="catalog-toolbar">
                    <div class="catalog-sorting">
                        <form action="<?php echo base_url($catalog->slug); ?>" method="get" id="sort-form">
                            <label for="sort-select">Sắp xếp:</label>
                            <select name="sort" id="sort-select" onchange="$('#sort-form').submit()">
                                <option value="default" <?php echo $sort == 'default' ? 'selected' : ''; ?>>Mặc định</option>
                                <option value="price-asc" <?php echo $sort == 'price-asc' ? 'selected' : ''; ?>>Giá tăng dần</option>
                                <option value="price-desc" <?php echo $sort == 'price-desc' ? 'selected' : ''; ?>>Giá giảm dần</option>
                                <option value="name-asc" <?php echo $sort == 'name-asc' ? 'selected' : ''; ?>>Tên A-Z</option>
                                <option value="name-desc" <?php echo $sort == 'name-desc' ? 'selected' : ''; ?>>Tên Z-A</option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="catalog-products">
                    <div class="row">
                        <?php if(!empty($list)): ?>
                            <?php foreach($list as $row): ?>
                            <div class="col-6 col-md-4">
                                <div class="product-item">
                                    <?php if($row->discount > 0): ?>
                                    <div class="product-badge sale">-<?php echo $row->discount; ?>%</div>
                                    <?php endif; ?>
                                    
                                    <div class="product-item-image">
                                        <a href="<?php echo base_url($row->slug.'-p'.$row->id); ?>">
                                            <img src="<?php echo base_url('upload/product/'.$row->image_link); ?>" alt="<?php echo $row->name; ?>" class="img-fluid">
                                        </a>
                                    </div>
                                    
                                    <div class="product-item-content">
                                        <h3 class="product-item-title">
                                            <a href="<?php echo base_url($row->slug.'-p'.$row->id); ?>"><?php echo $row->name; ?></a>
                                        </h3>
                                        
                                        <div class="product-item-price">
                                            <?php if($row->discount > 0): ?>
                                                <?php $price_new = $row->price - $row->price * $row->discount / 100; ?>
                                                <?php echo number_format($price_new); ?> VNĐ
                                                <del><?php echo number_format($row->price); ?> VNĐ</del>
                                            <?php else: ?>
                                                <?php echo number_format($row->price); ?> VNĐ
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
                        <?php else: ?>
                            <div class="col-12">
                                <div class="alert alert-info">Không có sản phẩm nào trong danh mục này.</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="catalog-pagination">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
            </div>
        </div>
    </div>
</div> 