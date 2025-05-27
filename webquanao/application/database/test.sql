SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `cart`;
DROP TABLE IF EXISTS `order`;
DROP TABLE IF EXISTS `transaction`;
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `usercoupon`;
DROP TABLE IF EXISTS `coupon`;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `product`;
DROP TABLE IF EXISTS `discount`;
DROP TABLE IF EXISTS `catalog`;
DROP TABLE IF EXISTS `admin`;
DROP TABLE IF EXISTS `slider`;
<<<<<<< HEAD
DROP TABLE IF EXISTS `shipping_tracking`;
DROP TABLE IF EXISTS `shipping_fee_rules`;
=======
>>>>>>> 07f5f07ecc3cb82232e4892372fd45eaf4dc5a76

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE IF NOT EXISTS `admin` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
  `password` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` TINYINT(2) NOT NULL,
  `created` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `catalog` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` INT(11) DEFAULT NULL,
  `sort_order` TINYINT(4) NOT NULL DEFAULT 0,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`parent_id`) REFERENCES `catalog`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `discount` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
	`description` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	`measure` TINYINT(1) NOT NULL DEFAULT 0,
	`value` INT(11) NOT NULL DEFAULT 0,
	`min_price` INT(11) NOT NULL DEFAULT 0,
	`start_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`end_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`status` TINYINT(1) NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `product` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `catalog_id` INT(11) NOT NULL,
  `name` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
	`origin_price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `discount_id` INT(11) DEFAULT NULL,
  `image_link` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_list` TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
	`view` INT(11) NOT NULL DEFAULT 0,
  `buyed` INT(11) NOT NULL DEFAULT 0,
  `rate_total` INT(11) NOT NULL DEFAULT 0,
  `rate_count` INT(11) NOT NULL DEFAULT 0,
	`stock` INT(11) NOT NULL DEFAULT 0,
	`status` TINYINT(1) NOT NULL DEFAULT 0,
  `created` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`catalog_id`) REFERENCES `catalog`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`discount_id`) REFERENCES `discount`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
  `password` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` VARCHAR(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
<<<<<<< HEAD
  `city` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `district` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ward` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` DATETIME NOT NULL,
  `is_verified` TINYINT(1) NOT NULL DEFAULT 0,
  `date_modified` DATETIME DEFAULT NULL,
=======
  `created` INT(11) NOT NULL,
  `is_verified` TINYINT(1) NOT NULL DEFAULT 0,
>>>>>>> 07f5f07ecc3cb82232e4892372fd45eaf4dc5a76
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `coupon` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`code` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
	`description` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	`type` TINYINT(1) NOT NULL DEFAULT 0,
	`measure` TINYINT(1) NOT NULL DEFAULT 0,
	`value` INT(11) NOT NULL DEFAULT 0,
	`min_price` INT(11) NOT NULL DEFAULT 0,
	`max_value` INT(11),
	`start_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`end_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`usage_limit` INT(11) NOT NULL DEFAULT 0,
	`used_count` INT(11) NOT NULL DEFAULT 0,
	`status` TINYINT(1) NOT NULL DEFAULT 0,
	`created` INT(11) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `usercoupon` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`user_id` INT(11) NOT NULL,
	`coupon_id` INT(11) NOT NULL,
	`used` TINYINT(1) NOT NULL DEFAULT 0,
	`used_at` INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`),
	FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`coupon_id`) REFERENCES `coupon`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `transaction` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `status` INT(11) NOT NULL DEFAULT 0,
  `user_id` INT(11) NOT NULL,
  `user_name` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_email` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_phone` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_address` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
<<<<<<< HEAD
  `user_city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_district` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_ward` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
=======
>>>>>>> 07f5f07ecc3cb82232e4892372fd45eaf4dc5a76
  `message` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
	`coupon_id` INT(11) DEFAULT NULL,
	`discount_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `payment` VARCHAR(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `order` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `qty` INT(11) NOT NULL DEFAULT 0,
  `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `status` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`transaction_id`) REFERENCES `transaction`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `product`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `comments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `rate` TINYINT(1) NOT NULL CHECK (`rate` BETWEEN 0 AND 5),
  `comment_content` TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `product`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `slider` (
  `id` INT(11) NOT NULL AUTO_INCREMENT, 
  `name` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_link` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` INT(11) NOT NULL DEFAULT 0,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
=======

CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL,
>>>>>>> 07f5f07ecc3cb82232e4892372fd45eaf4dc5a76
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rowid` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `options` text DEFAULT NULL,
  `image_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
<<<<<<< HEAD
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `shipping_fee_rules` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `min_distance_km` DECIMAL(5,2) DEFAULT 0,    
  `max_distance_km` DECIMAL(5,2) DEFAULT NULL,   
  `min_order_amount` DECIMAL(10,2) DEFAULT 0,  
  `max_order_amount` DECIMAL(10,2) DEFAULT NULL, 
  `shipping_fee` DECIMAL(10,2) NOT NULL,   
  `unit` TEXT,   
  `note` TEXT      
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `shipping_tracking` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` INT(11) NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'processing',
  `shipping_fee_rule_id` INT(11) DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `estimated_delivery` DATE DEFAULT NULL,
  `callback_url` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`transaction_id`) REFERENCES `transaction`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`shipping_fee_rule_id`) REFERENCES `shipping_fee_rules`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `admin` (`name`, `email`, `password`, `level`, `created`) VALUES
('Goo', 'admin@gmail.com', '1bbd886460827015e5d605ed44252251', 0, 2147483647),
('Mod đz', 'mod@gmail.com', '1bbd886460827015e5d605ed44252251', 1, 2147483647),
('Azura', 'lam@gmail.com', '1bbd886460827015e5d605ed44252251', 0, 1745048390);
=======
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `admin` (`name`, `email`, `password`, `level`, `created`) VALUES
('Goo', 'admin@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 0, 2147483647),
('Mod đz', 'mod@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 1, 2147483647);
>>>>>>> 07f5f07ecc3cb82232e4892372fd45eaf4dc5a76

INSERT INTO `catalog` (`name`, `description`, `parent_id`, `sort_order`, `created`) VALUES
('Thời trang', '', NULL, 1, '2017-04-22 05:35:21'),
('Bán chạy', '', NULL, 2, '2017-04-22 05:35:48'),
('Khuyến mại', '', NULL, 3, '2017-04-22 05:35:59'),
('Tin tức', '', NULL, 4, '2017-04-22 05:36:13'),
('Giỏ hàng', '', NULL, 6, '2017-04-22 05:36:49'),
('Liên hệ', '', NULL, 5, '2017-04-22 05:37:02'),
('Thời trang nam', '', 1, 1, '2017-04-22 05:37:23'),
('Thời trang nữ', '', 1, 2, '2017-04-22 05:37:36'),
('Quần áo gia đình', '', 1, 3, '2017-04-22 05:37:50'),
('Áo phông nam', '', 7, 1, '2017-04-22 09:08:19'),
('Áo sơ mi nam', '', 7, 2, '2017-04-22 09:08:36'),
('Quần Jeans', '', 7, 3, '2017-04-22 09:09:01'),
('Quần Kali', '', 7, 4, '2017-04-22 09:09:14'),
('Quần Short', '', 7, 5, '2017-04-22 09:09:31'),
('Áo thun nữ', '', 8, 1, '2017-04-22 09:09:46'),
('Áo sơ mi nữ', '', 8, 2, '2017-04-22 09:10:10'),
('Đầm, váy', '', 8, 3, '2017-04-22 09:23:39'),
('Áo công sở', '', 8, 4, '2017-04-22 09:23:57'),
('Áo gia đình hè', '', 9, 1, '2017-04-22 09:25:55'),
('Áo váy gia đình', '', 9, 2, '2017-04-22 09:26:21'),
('Mẹ và bé', '', 9, 4, '2017-04-22 09:26:34');

INSERT INTO `discount` (`name`, `description`, `measure`, `value`, `min_price`, `start_date`, `end_date`, `status`) VALUES
('Giảm giá 100k', 'Giảm giá 100k cho đơn hàng từ 200.000đ', 0, 100000, 200000, '2025-04-01 05:35:21', '2025-04-30 05:35:21', 1),
('Giảm giá 20%', 'Giảm giá 20% cho đơn hàng từ 100.000đ', 1, 20, 100000, '2025-04-01 05:35:21', '2025-04-30 05:35:21', 1);

INSERT INTO `product` (`catalog_id`, `name`, `content`, `origin_price`, `price`, `discount_id`, `image_link`, `image_list`, `view`, `buyed`, `rate_total`, `rate_count`, `stock`, `status`, `created`) VALUES
(16, 'Viền Cổ Hoa', '<p><a href="https://www.sendo.vn/ao-so-mi-nu.htm">&Aacute;o Sơ Mi Nữ</a>&nbsp;Viền Cổ Hoa 3D Thiết Kế Cổ Tr&ograve;n Viền Sọc Đen, Kết N&uacute;t Cổ Sau Lưng, Tay X&ograve;e Duy&ecirc;n D&aacute;ng, Kết Hoa 3D Th&ecirc;m Phần Nữ T&iacute;nh Cho Ph&aacute;i Đẹp, Chất Liệu Voan Mềm Mại, Tho&aacute;ng M&aacute;t</p>\r\n\r\n<p><strong>Chất Liệu:</strong>&nbsp;Voan Mềm Mại, Tho&aacute;ng M&aacute;t</p>\r\n\r\n<p><strong>M&agrave;u Sắc:</strong>&nbsp;T&iacute;m, Hồng</p>\r\n\r\n<p><strong>Kiểu D&aacute;ng:</strong>&nbsp;Thiết Kế Cổ Tr&ograve;n Viền Sọc Đen, Kết N&uacute;t Cổ Sau Lưng, Tay X&ograve;e Duy&ecirc;n D&aacute;ng, Kết Hoa 3D Th&ecirc;m Phần Nữ T&iacute;nh Cho Ph&aacute;i Đẹp</p>\r\n\r\n<p><strong>K&iacute;ch Thước:</strong>&nbsp;Size S - D&agrave;i &Aacute;o: 60, Rộng Vai: 28 - 32, V&ograve;ng Ngực: 74 - 84 ( Ph&ugrave; Hợp Với Bạn Nữ Dưới 50kg)&nbsp;</p>\r\n\r\n<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Size M - D&agrave;i &Aacute;o: 62, Rộng Vai: 29 - 33, V&ograve;ng Ngực: 76 - 86&nbsp;( Ph&ugrave; Hợp Với Bạn Nữ Dưới 55kg)</p>\r\n\r\n<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Size L - D&agrave;i &Aacute;o: 63, Rộng Vai: 31 - 35, V&ograve;ng Ngực: 84 -&nbsp;94( Ph&ugrave; Hợp Với Bạn Nữ Dưới 60kg)</p>\r\n', '179000.00', '179000.00',1, 'ao-so-mi-nu-vien-co-hoa-31.jpg', '["ao-so-mi-nu-vien-co-hoa-3d1.jpg","ao-so-mi-nu-vien-co-hoa-3dl1.jpg","ao-so-mi-nu-vien-co-hoa-3dla1.jpg"]', 24, 1, 14, 3, 100, 1, 1493983674),
(16, 'cổ trụ thắt nơ', '', '255000.00', '255000.00', 1, 'hang-nhap-so-mi-nu-co-tru-that-no-sm1.jpg', '["hang-nhap-so-mi-nu-co-tru-that-no.jpg","hang-nhap-so-mi-nu-co-tru-that-no-sm125-1.jpg"]', 2, 1, 4, 1, 100, 1, 1493983674),
(16, 'ẢO KIỂU HÀN QUỐC', '<p>ẢO KIỂU H&Agrave;N QUỐC V0040&nbsp;&nbsp;tay lỡ l&agrave; gu chủ yếu cho những ng&agrave;y thu. Nếu như h&egrave; bạn c&oacute; thể t&aacute;o bạo diện một chiếc sơ mi kh&ocirc;ng tay hay kiểu cổ ph&oacute;ng kho&aacute;ng cho thời trang c&ocirc;ng sở th&igrave; sang thu sẽ k&iacute;n đ&aacute;o hơn nhiều với kiểu sơ mi tay lỡ hoặc d&aacute;ng d&agrave;i tay đều ph&ugrave; hợp.</p>\r\n\r\n<p>Những mẫu sơ mi thiết kế tay lỡ vẫn sử dụng gam đơn hoặc họa tiết nếu muốn mix ph&ugrave; hợp c&ugrave;ng quần t&acirc;y, jean hay ch&acirc;n v&aacute;y ăn &yacute;.</p>\r\n\r\n<p>ẢO KIỂU&nbsp;<a href="https://www.sendo.vn/han-quoc.htm">H&Agrave;N QUỐC</a>&nbsp;V0040 với c&aacute;c th&ocirc;ng tin như sau:</p>\r\n\r\n<p>+ Mẫu m&atilde;: như h&igrave;nh;</p>\r\n\r\n<p>+ Xuất xứ: Việt Nam</p>\r\n\r\n<p>+ M&agrave;u sắc: Hồng, xanh, trắng, t&iacute;m</p>\r\n\r\n<p>+ Kiểu d&aacute;ng: tay lỡ, vạt ngang, cổ tr&ograve;n k&egrave;m d&acirc;y chuyền phụ kiện;</p>\r\n\r\n<p>+ Size: S, M, L, XL</p>\r\n', '300000.00', '300000.00', 1, 'ao-kieu-han-quoc-v0040-1m4G3-8352b3_simg_d0daf0_800x1200_max.jpg', '["ao-kieu-han-quoc-v0040-1m4G3-7118e0_simg_d0daf0_800x1200_max.jpg","ao-kieu-han-quoc-v0040-1m4G3-131527_simg_d0daf0_800x1200_max.jpg"]', 42, 4, 11, 3, 100, 1, 1493983674),
(18, 'phối ren', '<p>ẢO KIỂU H&Agrave;N QUỐC V0040&nbsp;&nbsp;tay lỡ l&agrave; gu chủ yếu cho những ng&agrave;y thu. Nếu như h&egrave; bạn c&oacute; thể t&aacute;o bạo diện một chiếc sơ mi kh&ocirc;ng tay hay kiểu cổ ph&oacute;ng kho&aacute;ng cho thời trang c&ocirc;ng sở th&igrave; sang thu sẽ k&iacute;n đ&aacute;o hơn nhiều với kiểu sơ mi tay lỡ hoặc d&aacute;ng d&agrave;i tay đều ph&ugrave; hợp.</p>\r\n\r\n<p>Những mẫu sơ mi thiết kế tay lỡ vẫn sử dụng gam đơn hoặc họa tiết nếu muốn mix ph&ugrave; hợp c&ugrave;ng quần t&acirc;y, jean hay ch&acirc;n v&aacute;y ăn &yacute;.</p>\r\n\r\n<p>ẢO KIỂU&nbsp;<a href="https://www.sendo.vn/han-quoc.htm">H&Agrave;N QUỐC</a>&nbsp;V0040 với c&aacute;c th&ocirc;ng tin như sau:</p>\r\n\r\n<p>+ Mẫu m&atilde;: như h&igrave;nh;</p>\r\n\r\n<p>+ Xuất xứ: Việt Nam</p>\r\n\r\n<p>+ M&agrave;u sắc: Hồng, xanh, trắng, t&iacute;m</p>\r\n\r\n<p>+ Kiểu d&aacute;ng: tay lỡ, vạt ngang, cổ tr&ograve;n k&egrave;m d&acirc;y chuyền phụ kiện;</p>\r\n\r\n<p>+ Size: S, M, L, XL</p>\r\n', '280000.00', '280000.00',1, 'hang-nhap-cao-cap-so-mi-nu-phoi-ren-sm115-1m4G3-HuHbF8_simg_d0daf0_800x1200_max.jpg', '["hang-nhap-cao-cap-so-mi-nu-phoi-ren-sm115-1m4G3-q1bUZr_simg_d0daf0_800x1200_max.jpg"]', 16, 7, 18, 4, 100, 1, 1493983674),
(17, 'Đầm maxi phối ren cao cấp', '<p>Chất liệu: Chiffon phối ren cao cấp<br />\r\nM&agrave;u sắc: Đen, hồng<br />\r\nK&iacute;ch thước: S,M,L,XL<br />\r\nXuất Xứ : Việt Nam&nbsp;</p>\r\n\r\n<p>+ size S: Chiều d&agrave;i đầm: 130cm, Ngực 78-80cm, Eo 64-68cm, M&ocirc;ng 84-86cm</p>\r\n\r\n<p>+ size M: Chiều d&agrave;i đầm: 130cm, Ngực 80-84cm, Eo 68-72cm, M&ocirc;ng 86-90cm<br />\r\n+ size L: Chiều d&agrave;i đầm: 130cm, Ngực 84-88cm, Eo 72-76cm, M&ocirc;ng 90-96cm<br />\r\n+ size XL: Chiều d&agrave;i đầm: 130cm, Ngực 88-92cm, Eo 76-78cm, M&ocirc;ng 96-100cm</p>\r\n', '720000.00', '720000.00', 1, 'dam-maxi-phoi-ren-cao-cap-1m4G3-QXVTv3_simg_d0daf0_800x1200_max.jpg', '["dam-maxi-phoi-ren-cao-cap-1m4G3-sh6ofY_simg_d0daf0_800x1200_max.jpg","dam-maxi-phoi-ren-cao-cap-1m4G3-sUX4Gv_simg_d0daf0_800x1200_max.jpg","dam-maxi-phoi-ren-cao-cap-1m4G3-VEbARk_simg_d0daf0_800x1200_max.jpg"]', 27, 3, 9, 2, 100, 1, 0),
(17, 'Đầm ren Thái form dài', '<p><em>* Chất liệu:&nbsp;</em>Ren Th&aacute;i cao cấp, lớp l&oacute;t trong d&agrave;y dặn</p>\r\n\r\n<p>*&nbsp;<em>Kiểu d&aacute;ng</em>&nbsp;Đầm kh&ocirc;ng tay, cổ tr&ograve;n, Ch&acirc;n v&aacute;y x&ograve;e, d&agrave;i ngang bắp ch&acirc;n. Kiểu d&aacute;ng mềm mại thướt tha đầy nữ t&iacute;nh</p>\r\n\r\n<p>*&nbsp;<em>M&atilde; sản phẩm:</em>&nbsp;DR 26</p>\r\n', '200000.00', '200000.00',2, 'dam-ren-thai-form-dai-1m4G3-9f2a11.jpg', '["dam-ren-thai-form-dai-1m4G3-38d74e.jpg","dam-ren-thai-form-dai-1m4G3-918972.jpg","dam-ren-thai-form-dai-1m4G3-d5e05d.jpg"]', 5, 1, 4, 1, 100, 1, 1493983674),
(18, 'áo kiểu công sở', '<p>&Aacute;o kiểu mang đến vẻ đẹp nữ t&iacute;nh, dịu d&agrave;ng cho n&agrave;ng!</p>\r\n\r\n<p>Với chất vải v&ocirc; c&ugrave;ng mềm mại v&agrave; nhẹ nh&agrave;ng, chiếc &aacute;o kiểu l&agrave;m từ chất liệu voan n&agrave;y lu&ocirc;n ph&aacute;t huy v&agrave; t&ocirc; điểm được vẻ đẹp nữ t&iacute;nh, dịu d&agrave;ng của bạn g&aacute;i. Nhất l&agrave; với những kiểu d&aacute;ng cổ b&egrave;o c&aacute;ch điệu hay họa tiết xinh xắn lại c&agrave;ng gi&uacute;p n&agrave;ng khoe th&ecirc;m được sự điệu đ&agrave; v&agrave; ấn tượng của m&igrave;nh. Bởi thế, chiếc &aacute;o n&agrave;y v&ocirc; c&ugrave;ng ph&ugrave; hợp với những c&ocirc; n&agrave;ng c&oacute; phong c&aacute;ch thời trang nữ t&iacute;nh, nhẹ nh&agrave;ng.</p>\r\n', '300000.00', '300000.00', 2, 'ao-kieu-cong-so-a0122-1m4G3-ZebjMN_simg_d0daf0_800x1200_max.png', '["ao-kieu-cong-so-a0122-1m4G3-o0hhot_simg_d0daf0_800x1200_max.png","ao-kieu-cong-so-a0122-1m4G3-qXBUW2_simg_d0daf0_800x1200_max.png","ao-kieu-cong-so-a0122-1m4G3-vS6ei3_simg_d0daf0_800x1200_max.png"]', 2, 1, 4, 1, 100, 1, 1493983674),
(17, 'Đầm ren tay dài tiểu thư', '<p>Đầm ren tay d&agrave;i tiểu thư duy&ecirc;n d&aacute;ng nữ t&iacute;nh trị gi&aacute; 450.000 VNĐ nay chỉ c&ograve;n 350.000 VNĐ</p>\r\n\r\n<p>C&aacute;c th&ocirc;ng tin như sau:</p>\r\n\r\n<p>+ Mẫu m&atilde;: như h&igrave;nh;</p>\r\n\r\n<p>+ Xuất xứ: Việt Nam</p>\r\n\r\n<p>+ M&agrave;u sắc: Hồng, xanh, trắng, t&iacute;m</p>\r\n\r\n<p>+ Kiểu d&aacute;ng: tay lỡ, vạt ngang, cổ tr&ograve;n k&egrave;m d&acirc;y chuyền phụ kiện;</p>\r\n\r\n<p>+ Size: S, M, L, XL</p>\r\n', '450000.00', '450000.00', 1, 'Dam_ren_den_tay_dai_tieu_thu_(3).jpg', '["Dam_ren_den_tay_dai_tieu_thu_(2).jpg","Dam_ren_den_tay_dai_tieu_thu_(13).jpg","Dam_ren_tieu_thu_tay_dai_(1).jpg"]', 20, 6, 13, 3, 100, 1, 1493983674),
(15, 'Áo Thun Nữ ROMA', '<p>►Chất liệu cao cấp COTTON 4 CHIỀU mềm mại<br />\r\n►Co giãn tốt ; thoáng mát     ►Thiết kế thời trang<br />\r\n►Kiểu dáng đa phong cách   ►Đường may tinh tế sắc sảo<br />\r\n► Áo thun nữ được thiết kế và sản xuất bởi Trần Doanh mang vể đẹp trẻ trung năng động nhưng không kém phần duyên dáng.<br />\r\n►Áo được thiết kế đẹp, chuẩn form, đường may sắc xảo, vải cotton dày, mịn, thấm hút mồ hôi tạo sự thoải mái khi mặc!<br />\r\n►Thích hợp cho sự kết hợp vứi quần jean, sọt,legging!</p>\r\n', '180000.00', '180000.00', 1, 'ao-thun-ao-phong-nu-hoa-tiet-chu-roma.jpg', '["ao-thun-ao-phong-nu-hoa-tiet-chu-roma-ca-tin.jpg","ao-thun-ao-phong-nu-hoa-tiet-chu-roma-ca-tinh.jpg"]', 2, 1, 4, 1, 100, 1, 1493983674),
(15, 'ÁO THU NGỰA MINI', '<p>ẢO KIỂU H&Agrave;N QUỐC V0040&nbsp;&nbsp;tay lỡ l&agrave; gu chủ yếu cho những ng&agrave;y thu. Nếu như h&egrave; bạn c&oacute; thể t&aacute;o bạo diện một chiếc sơ mi kh&ocirc;ng tay hay kiểu cổ ph&oacute;ng kho&aacute;ng cho thời trang c&ocirc;ng sở th&igrave; sang thu sẽ k&iacute;n đ&aacute;o hơn nhiều với kiểu sơ mi tay lỡ hoặc d&aacute;ng d&agrave;i tay đều ph&ugrave; hợp.</p>\r\n\r\n<p>Những mẫu sơ mi thiết kế tay lỡ vẫn sử dụng gam đơn hoặc họa tiết nếu muốn mix ph&ugrave; hợp c&ugrave;ng quần t&acirc;y, jean hay ch&acirc;n v&aacute;y ăn &yacute;.</p>\r\n\r\n<p>ẢO KIỂU&nbsp;<a href="https://www.sendo.vn/han-quoc.htm">H&Agrave;N QUỐC</a>&nbsp;với c&aacute;c th&ocirc;ng tin như sau:</p>\r\n\r\n<p>+ Mẫu m&atilde;: như h&igrave;nh;</p>\r\n\r\n<p>+ Xuất xứ: Việt Nam</p>\r\n\r\n<p>+ M&agrave;u sắc: Hồng, xanh, trắng, t&iacute;m</p>\r\n\r\n<p>+ Kiểu d&aacute;ng: tay lỡ, vạt ngang, cổ tr&ograve;n k&egrave;m d&acirc;y chuyền phụ kiện;</p>\r\n\r\n<p>+ Size: S, M, L, XL</p>\r\n', '80000.00', '80000.00', 1, 'ao-thu-ngua-mini-1m4G3-57c588_simg_d0daf0_800x1200_max.jpg', '["ao-thu-ngua-mini-1m4G3-9f6f25_simg_d0daf0_800x1200_max.jpg","ao-thu-ngua-mini-1m4G3-a959f5_simg_d0daf0_800x1200_max.jpg"]', 35, 3, 5, 1, 100, 1, 1493983674),
(15, ' Áo Thun Form Rộng', '<p>- &Aacute;o thun nữ trẻ trung c&oacute; thiết kế năng động với cổ tr&ograve;n, tay ngắn mang lại cho bạn sự thoải m&aacute;i khi mặc.<br />\r\n- Thiết kế form rộng c&aacute; t&iacute;nh cho bạn lu&ocirc;n cảm thấy dễ chịu khi mặc trong thời gian d&agrave;i.<br />\r\n- In họa tiết chữ đơn giản, trẻ trung tạo n&eacute;t c&aacute; t&iacute;nh ri&ecirc;ng cho sản phẩm.<br />\r\n- Đường may chắc chắn, cẩn thận cho bạn tự tin hơn trong vận động.<br />\r\n- Chất liệu: thun cotton 4 chiều co gi&atilde;n tốt, thấm h&uacute;t mồ h&ocirc;i hiệu quả.<br />\r\n- Size: freesize<br />\r\n- M&agrave;u sắc: trắng, đen, xanh biển</p>\r\n', '129000.00', '129000.00', 2, 'ao-thun-ao-phong-nu-eiffel-ca-tinh-msat28-1m4G3-PP5C91_simg_d0daf0_800x1200_max.jpg', '["ao-thun-ao-phong-nu-eiffel-ca-tinh-msat28-1m4G3-LpJZdC_simg_d0daf0_800x1200_max.jpg","ao-thun-ao-phong-nu-eiffel-ca-tinh-msat28-1m4G3-ZyFQ9v_simg_d0daf0_800x1200_max.jpg"]', 8, 2, 4, 1, 100, 1, 1493983674),
(17, 'ĐẦM ÔM BODY CỔ ĐÍNH HẠT', '<p>CHẤT LIỆU : THUN COTON CO GI&Atilde;N THO&Aacute;NG M&Aacute;T DỂ CHIỆU&nbsp;</p>\r\n\r\n<p>TH&Iacute;CH HỢP MỌI HOẠT ĐỘNG : C&Ocirc;NG SỞ , DỰ TIỆC , DẠO PHỐ , ĐI BIỂN ....</p>\r\n\r\n<p>SIZE :</p>\r\n\r\n<p>M&Agrave;U : CAM N&Acirc;U, X&Aacute;M ĐEN ( &Ocirc; M&Agrave;U CHỌN L&Agrave; X&Aacute;M ) XANH LAM , TRẮNG&nbsp;</p>\r\n', '200000.00', '200000.00', 1, 'dam-om-body-co-dinh-hat-1m4G3-22CEL4_simg_d0daf0_800x1200_max.jpg', '["dam-om-body-co-dinh-hat-1m4G3-qrWR6I_simg_d0daf0_800x1200_max.jpg","dam-om-body-co-dinh-hat-1m4G3-tVjWlK_simg_d0daf0_800x1200_max.jpg","dam-om-body-co-dinh-hat-1m4G3-XI1vLB_simg_d0daf0_800x1200_max.jpg"]', 3, 2, 4, 1, 100, 1, 1493983674),
(17, 'ĐẦM XÒE PHỐI REN CAO CẤP', '<p>Chất liệu ren&nbsp;<a href="https://www.sendo.vn/cao-cap.htm">cao cấp</a>&nbsp;cho 1 bạn 1 phong c&aacute;ch sang chảnh thu đ&ocirc;ng năm nay ,với c&aacute;c m&agrave;u diệu ,nồng nằng quyến rũ kh&ocirc;ng thể n&agrave;o kh&ocirc;ng cuốn h&uacute;t đươc tất cả &aacute;nh nh&igrave;n xung quanh h&ograve;a quyện v&agrave;o dạng x&ograve;e cổ điển&nbsp;<a href="https://www.sendo.vn/phoi-ren.htm">phối ren</a>&nbsp; cao cấp .<br />\r\nM&agrave;u : đen , xanh , đỏ&nbsp;<br />\r\nSize : M 45 - 52 kg t&ugrave;y theo chiều cao&nbsp;<br />\r\nXưởng nhận may gia c&ocirc;ng tất cả c&aacute;c mặt h&agrave;ng thời trang nam nữ&nbsp;<br />\r\nVới chất liệu bắt mắt v&agrave; chất lượng rất ok nắm bắt xu hướng thời trang thu đ&ocirc;ng năm nay&nbsp;<br />\r\nMẫu v&aacute;y x&ograve;e ren l&agrave; sự lựa chọn tốt nhất cho bạn.</p>\r\n', '350000.00', '350000.00', 1, 'dam-xoe-phoi-ren-cao-cap-1m4G3-lsWUnT.jpg', '["dam-xoe-phoi-ren-cao-cap-1m4G3-AQuuDj.jpg","dam-xoe-phoi-ren-cao-cap-1m4G3-FGCII2.jpg","dam-xoe-phoi-ren-cao-cap-1m4G3-qxyXGj.jpg","dam-xoe-phoi-ren-cao-cap-1m4G3-ztYeGq.jpg"]',  4, 1, 4, 1, 100, 1, 1493983674),
(19, 'Áo gia đình AG0560', '<p><strong><a href="http://aothun24h.vn/san-pham/170/Ao-gia-dinh.html" target="_blank">&Aacute;o gia đ&igrave;nh</a>&nbsp;kẻ sọc ngang</strong>&nbsp;rất được ưa chuộng hiện nay, d&ugrave; l&agrave; ở lứa tuổi n&agrave;o th&igrave; thời trang kẻ sọc cũng lu&ocirc;n mang đ&ecirc;n cho người mặc một phong c&aacute;ch trẻ trung năng động v&agrave; c&aacute; t&iacute;nh.</p>\r\n\r\n<p>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kh&ocirc;ng mặc sọc ngang từ đầu đến ch&acirc;n l&agrave; b&iacute; quyết gia đ&igrave;nh bạn n&ecirc;n biết.</p>\r\n\r\n<p>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Chọn chất liệu mềm v&agrave; phom d&aacute;ng su&ocirc;n rộng để che khuyết điểm.</p>\r\n\r\n<p>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Chọn sọc kẻ ngang vừa phải, kh&ocirc;ng đụng tới sọc to.</p>\r\n', '580000.00', '580000.00', 2, 'ao-gia-dinh-AG0560-1.jpg', '["ao-gia-dinh-AG0560.jpg","ao-gia-dinh-AG0560-2.jpg","ao-gia-dinh-AG0560-3.jpg","ao-gia-dinh-AG0560-4.jpg"]', 4, 3, 13, 3, 100, 1, 1493983674),
(19, 'Áo gia đình AG0554', '<p><strong>Th&ocirc;ng tin về sản phẩm:</strong></p>\r\n\r\n<p>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kiểu &aacute;o : &Aacute;o thun cổ tr&ograve;n tay ngắn.</p>\r\n\r\n<p>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;M&agrave;u sắc: Nhiều m&agrave;u sắc để lựa chọn.</p>\r\n\r\n<p>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Chất liệu: Thun cotton 4 chiều.</p>\r\n\r\n<p>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Size &aacute;o: Đủ size &aacute;o để lựa chọn.</p>\r\n\r\n<p>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C&ocirc;ng nghệ in: Mimaki của Nhật Bản.</p>\r\n', '500000.00', '500000.00', 1, 'ao-gia-dinh-AG0554.jpg', '["ao-gia-dinh-AG0554-1.jpg","ao-gia-dinh-AG0554-2.jpg","ao-gia-dinh-AG0554-3.jpg","ao-gia-dinh-AG0554-4.jpg"]', 36, 1, 14, 4, 100, 1, 1493983674),
(20, 'ÁO VÁY GIA ĐÌNH AG0430 - AG0430', '<p><strong>Chất liệu cotton tho&aacute;ng m&aacute;t</strong></p>\r\n\r\n<p>Chất liệu cotton 4 chiều tho&aacute;ng m&aacute;t, mềm mại, dễ giặt, nhanh kh&ocirc; v&agrave; h&uacute;t ẩm tốt.</p>\r\n\r\n<p><strong>Thiết kế đơn giản m&agrave; tinh tế</strong></p>\r\n\r\n<p>Thiết kế &aacute;o đơn giản, trẻ trung thoải m&aacute;i cho gia đình bạn khi mặc, sửa lại áo mi&ecirc;̃n phí khi mặc quá r&ocirc;̣ng hoặc quá dài.</p>\r\n', '900000.00', '900000.00', 1, 'ao-vay-gia-dinh-ag0515-1m4G3-4UKwpv_simg_d0daf0_800x1200_max.jpg', '["ao-vay-gia-dinh-ag0515-1m4G3-pPlrtD_simg_d0daf0_800x1200_max.jpg","ao-vay-gia-dinh-ag0515-1m4G3-t5DoaE_simg_d0daf0_800x1200_max.jpg"]', 1, 1, 5, 1, 100, 1, 0),
(21, 'ComBo Đầm Đôi PENDI Xinh Xắn', '<p><strong>TH&Ocirc;NG TIN SẢN PHẨM&nbsp;</strong></p>\r\n\r\n<p>- Chất liệu : thun</p>\r\n\r\n<p>- Năm sản xuất : 2016</p>\r\n\r\n<p>- Xuất xứ : Việt nam ( c&ocirc;ng ty th&aacute;i ho&agrave;ng sx)</p>\r\n\r\n<p>- M&agrave;u sắc : xanh, đỏ , hồng</p>\r\n\r\n<p>- K&iacute;ch thước : Freesize d&agrave;nh cho mẹ từ 43</p>\r\n', '390000.00', '390000.00', 1, 'combo-dam-doi-pendi-xinh-xan-th08560-1m4G3-GmhUQZ.jpg', '["combo-dam-doi-pendi-xinh-xan-th08560-1m4G3-mPSYrq.jpg","combo-dam-doi-pendi-xinh-xan-th08560-1m4G3-tp7Ma5.jpg","combo-dam-doi-pendi-xinh-xan-th08560-1m4G3-Xd5kQ5.jpg"]', 2, 1, 4, 1, 100, 1, 1493983674),
(21, 'COMBO ĐẦM KÈM ÁO KHOÁC CHOÀNG', '<p><strong>TH&Ocirc;NG TIN SẢN PHẨM&nbsp;</strong></p>\r\n\r\n<p>- Chất liệu : thun</p>\r\n\r\n<p>- Năm sản xuất : 2016</p>\r\n\r\n<p>- Xuất xứ : Việt nam ( c&ocirc;ng ty th&aacute;i ho&agrave;ng sx)<br />\r\n- M&agrave;u sắc : caro&nbsp;</p>\r\n\r\n<p>- K&iacute;ch thước : Freesize d&agrave;nh cho mẹ từ 43-55kg - size M từ 13-17kg- L &nbsp;từ 17-22kg<br />\r\n&nbsp;</p>\r\n', '380000.00', '380000.00', 2, 'combo-dam-kem-ao-khoac-choang-thoi-trang-th08603-gs195-1m4G3-1SqJve.jpg', '["combo-dam-kem-ao-khoac-choang-thoi-trang-th08603-gs195-1m4G3-FWKQKq.jpg"]', 32, 1, 4, 1, 100, 1, 1493983674),
(21, 'COMBO ĐÔI ĐẦM MẸ VÀ BÉ MICKEY', '<p>T&ecirc;n sp:&nbsp;<a href="https://ban.sendo.vn/product">Combo &aacute;o thun mẹ v&agrave; b&eacute; Mickey</a><br />\r\n<br />\r\nChất liệu: Thun cotton c&aacute; sấu cao cấp mềm mại thoải mai khi mặc cho c&aacute;c n&agrave;ng<br />\r\n<br />\r\nM&agrave;u sắc: &nbsp; &nbsp;Hồng - Trắng 2 m&agrave;u 100% như h&igrave;nh ảnh minh họa. Gam m&agrave;u trẻ trung cho c&aacute;c n&agrave;ng<br />\r\n<br />\r\nThiết kế đơn giản kiểu đầm su&ocirc;ng, form rộng , cổ tr&ograve;n tay lỡ &nbsp; ph&ocirc;i m&agrave;u &nbsp;trẻ trung xin xắn cho&nbsp;<a href="https://www.sendo.vn/me-va-be.htm">mẹ v&agrave; b&eacute;</a><br />\r\n<br />\r\nPh&ugrave; hợp với c&aacute;c mặt dao phố, du lịch, mặc nh&agrave;., đi l&agrave;m, dự tiệc, event ...<br />\r\n<br />\r\nK&iacute;ch thước: Free Size<br />\r\n<br />\r\nCho b&eacute; từ 15 ---&gt; 22 kg</p>\r\n', '180000.00', '180000.00', 2, 'combo-doi-dam-me-va-be-mickey-ddp08444-1.jpg', '["combo-doi-dam-me-va-be-mickey-ddp08444.jpg","combo-doi-dam-me-va-be-mickey-ddp08444-1m4G.jpg","combo-doi-dam-me-va-be-mickey-ddp08444-1m4G3-6653ea_simg_d0daf0_800x1200_max.jpg"]', 0, 1, 4, 1, 100, 1, 1493983674),
(21, 'COMBO ĐẦM CẶP MẸ VÀ BÉ', '<p>Set đ&ocirc;i mẹ v&agrave; b&eacute; gồm :<br />\r\n&Aacute;o d&agrave;i tay + v&aacute;y yếm cho mẹ c&acirc;n nặng từ 43kg - 53kg<br />\r\n&Aacute;o d&agrave;i tay + quần yếm cho b&eacute; trai/ b&eacute; g&aacute;i c&acirc;n nặng từ 17kg- 24kg<br />\r\nM&agrave;u sắc y h&igrave;nh<br />\r\nChất cotton cao cấp d&agrave;y mịn đẹp. Bao d&agrave;y .<br />\r\nShop ko ship h&agrave;ng để xem hay l&yacute; do ko vừa ko th&iacute;ch ko hợp....<br />\r\nTất cả sp đều c&oacute; h&igrave;nh chụp đầy đủ n&ecirc;n kh&aacute;ch vui l&ograve;ng xem kỹ trước khi mua h&agrave;ng b&ecirc;n shop</p>\r\n', '400000.00', '400000.00', 1, 'combo-dam-cap-me-va-be-1m4G3-epzjq8_simg_d0daf0_800x1200_max.jpg', '["combo-dam-cap-me-va-be-1m4G3-hKwaQm_simg_d0daf0_800x1200_max.jpg","combo-dam-cap-me-va-be-1m4G3-SxVIlb_simg_d0daf0_800x1200_max.jpg","combo-dam-cap-me-va-be-1m4G3-WqmKco_simg_d0daf0_800x1200_max.jpg"]', 0, 1, 4, 1, 100, 1, 1493983674),
(21, 'COMBO ĐẦM REN MÙA XUÂN', '<p><strong>TH&Ocirc;NG TIN SẢN PHẨM&nbsp;</strong></p>\r\n\r\n<p>- Chất liệu : REN</p>\r\n\r\n<p>- Năm sản xuất : 2016</p>\r\n\r\n<p>- Xuất xứ : Việt nam&nbsp;</p>\r\n\r\n<p>- M&agrave;u sắc :đỏ</p>\r\n\r\n<p>- K&iacute;ch thước : Freesize từ 43-55k... size M từ 13-17. size L từ 17-25</p>\r\n', '450000.00', '450000.00', 1, 'combo-dam-ren-mua-xuan-cho-me-va-be-th08602-gs210-1m4G3-g4rMfx.jpg', '["combo-dam-ren-mua-xuan-cho-me-va-be-th08602-gs210-1m4G3-kwPno1.jpg"]', 19, 7, 22, 5, 100, 1, 1493983674),
(11, 'Phong Cách Phối Màu', '<p>Chất Liệu: Kaki Silk Thun</p>\r\n\r\n<p>M&agrave;u Sắc: Cổ&nbsp;Trắng Phối Đen, Cổ&nbsp;Trắng Phối Xanh Đen, Cổ Đen Phối Trắng, Cổ Đen Phối Xanh Đen</p>\r\n\r\n<p>Kiểu D&aacute;ng:&nbsp;Thiết Kế D&agrave;i Tay, Th&acirc;n Phối M&agrave;u Trẻ Trung</p>\r\n\r\n<p>Đơn Vị: Cm</p>\r\n\r\n<p>K&iacute;ch Thước: Size L - D&agrave;i &Aacute;o: 67, D&agrave;i Tay: 60, Rộng Vai: 37 - 41, V&ograve;ng Ngực: 78 - 88 (Ph&ugrave; Hợp Với Bạn Nam Dưới 60kg, Chiếu Cao Dưới 1,65 m&eacute;t)</p>\r\n\r\n<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Size XL - D&agrave;i &Aacute;o: 69, D&agrave;i Tay: 60, Rộng Vai: 39 - 43, V&ograve;ng Ngực: 80 - 90 (Ph&ugrave; Hợp Với Bạn Nam Dưới 65kg, Chiếu Cao Dưới 1,7 m&eacute;t)</p>\r\n\r\n<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Size XXL - D&agrave;i &Aacute;o: 70, D&agrave;i Tay: 61, Rộng Vai: 40 - 44, V&ograve;ng Ngực: 82 - 92 (Ph&ugrave; Hợp Với Bạn Nam Dưới 70kg, Chiếu Cao Dưới 1,75 m&eacute;t)</p>\r\n', '230000.00', '230000.00', 2, 'ao-so-mi-nam-phong-cach-phoi-mau-1m4G3-x9hhml.jpg', '["ao-so-mi-nam-phong-cach-phoi-mau-1m4G3-BSZiod.jpg","ao-so-mi-nam-phong-cach-phoi-mau-1m4G3-xL4zQp.jpg"]', 36, 1, 9, 2, 100, 1, 1493983674),
(11, 'Ngắn Tay Cao Cấp Kiểu Dáng Hàn Quốc', '<ul>\r\n	<li><strong><em>Sơ Mi Nam Ngắn Tay Cao Cấp</em> </strong>Kiểu dáng Hàn Quốc</li>\r\n	<li>Phom Dáng Slim Fix</li>\r\n	<li>Chất liệu 90% Cotton</li>\r\n	<li>Áo cao cấp, <strong>KHÔNG</strong> bai xù, mất phom sau thời gian dài sử dụng.</li>\r\n</ul>\r\n', '450000.00', '450000.00', 1, 'so-mi-nam-ngan-tay-cao-cap-kieu-dang-han-quoc-1m4G3-8aLJTO_simg_d0daf0_800x1200_max.jpg', '["so-mi-nam-ngan-tay-cao-cap-kieu-dang-han-quoc-1m4G3-6pRF6s_simg_d0daf0_800x1200_max.jpg","so-mi-nam-ngan-tay-cao-cap-kieu-dang-han-quoc-1m4G3-E232HF_simg_d0daf0_800x1200_max.jpg","so-mi-nam-ngan-tay-cao-cap-kieu-dang-han-quoc-1m4G3-F3VBLA_simg_d0daf0_800x1200_max.jpg"]', 2, 1, 9, 2, 100, 1, 1493983674),
(14, 'Quần kaki short nam - QS43', '<p><strong>Thông tin chi tiết sản phẩm</strong>:</p>\r\n\r\n<p>Tên sản phẩm : Quần kaki short nam cá tính-QS43</p>\r\n\r\n<p>- Mã sản phẩm : QS43</p>\r\n\r\n<p>- Chất liệu : vải kaki</p>\r\n\r\n<p>- Mầu sắc : xanh đen,xanh dương, nâu vàng</p>\r\n\r\n<p>- Kích cỡ : 28-29-30-31-32</p>\r\n\r\n<p>-Trọng lượng : 400g</p>\r\n', '165000.00', '165000.00', 1, 'quan-kaki-short-nam-qs43-1m4G3-Czuekh_simg_d0daf0_800x1200_max.jpg', '["quan-kaki-short-nam-qs43-1m4G3-3TUeRm_simg_d0daf0_800x1200_max.jpg","quan-kaki-short-nam-qs43-1m4G3-JsGgBd_simg_d0daf0_800x1200_max.jpg","quan-kaki-short-nam-qs43-1m4G3-lqqiMY_simg_d0daf0_800x1200_max.jpg"]', 5, 1, 9, 2, 100, 1, 1493983674),
(14, 'Quần short kaki nam - QKN44', '<p>Quần short&nbsp;<a href="https://www.sendo.vn/kaki-nam.htm">Kaki Nam</a></p>\r\n\r\n<p>Vải kaki loại 1, form chuẩn t&ocirc;n d&aacute;ng &nbsp;</p>\r\n\r\n<p>Size: 28-32</p>\r\n', '200000.00', '200000.00', 1, 'quan-short-kaki-nam-1m4G3-sexFoa_simg_d0daf0_800x1200_max.jpg', '["quan-short-kaki-nam-1m4G3-E4MW4M_simg_d0daf0_800x1200_max.jpg","quan-short-kaki-nam-1m4G3-iKaEX7_simg_d0daf0_800x1200_max.jpg","quan-short-kaki-nam-1m4G3-reyYEA_simg_d0daf0_800x1200_max.jpg"]', 2, 1, 4, 1, 100, 1, 1493983674),
(13, 'Quần kaki Nam Lịch Lãm - D36', '<p>Quần kaki nam lịch l&atilde;m</p>\r\n\r\n<p>Chất liệu vải kaki loại 1 d&agrave;y mịn</p>\r\n\r\n<p>C&oacute; đủ size 28,29,30,31,32</p>\r\n\r\n<p>Với 3 t&ocirc;ng m&agrave;u trầm đen,xanh đen rất dễ phối với &aacute;o thun,&aacute;o sơ mi,...tạo phong c&aacute;ch thanh lịch cho c&aacute;c bạn nam khi diện đến c&ocirc;ng sở, đi chơi,du lịch,...</p>\r\n', '169000.00', '169000.00', 1, 'quan-kaki-nam-lich-lam-1m4G3-NvjQo7_simg_d0daf0_800x1200_max.jpg', '["quan-kaki-nam-lich-lam-1m4G3-tyzFof_simg_d0daf0_800x1200_max.png","quan-kaki-nam-lich-lam-1m4G3-uSjiJP_simg_d0daf0_800x1200_max.jpg"]', 16, 1, 18, 4, 100, 1, 1493983674),
(13, 'QUẦN KAKI THUN JOGGER', '<p>Kiểu d&aacute;ng trẻ trung, t&ocirc;ng m&agrave;u, họa tiết lạ mắt dễ d&agrave;ng mix c&ugrave;ng &aacute;o thun tạo phong c&aacute;ch trẻ trung cho bạn trẻ.</p>\r\n\r\n<p>Thiết kế t&uacute;i 2 b&ecirc;n tiện dụng, bo lưng thun gi&uacute;p bạn thoải m&aacute;i khi vận động.</p>\r\n\r\n<p>Form d&aacute;ng d&agrave;i, chất liệu bố, d&agrave;y dặn, thấm h&uacute;t mồ h&ocirc;i bạn trai c&oacute; thể thoải m&aacute;i hoạt động</p>\r\n\r\n<p>Size : M, L</p>\r\n', '300000.00', '300000.00', 2, 'cu-cai-quan-kaki-thun-jogger-thoi-trang-mau-kem-qg06-1m4G3-7ec3c2_simg_d0daf0_800x1200_max.jpg', '["cu-cai-quan-kaki-thun-jogger-thoi-trang-mau-kem-qg06-1m4G3-3e0554_simg_d0daf0_800x1200_max.jpg","cu-cai-quan-kaki-thun-jogger-thoi-trang-mau-kem-qg06-1m4G3-63841e_simg_d0daf0_800x1200_max.jpg","cu-cai-quan-kaki-thun-jogger-thoi-trang-mau-kem-qg06-1m4G3-fd6df6_simg_d0daf0_800x1200_max.jpg"]', 23, 1, 4, 1, 100, 1, 1493983674);

<<<<<<< HEAD

INSERT INTO `user` -- mật khẩu: 12345678
(`name`, `email`, `password`, `phone`, `address`, `city`, `district`, `ward`, `created`, `is_verified`, `date_modified`) VALUES
('Nguyễn Văn A', 'nguyenvana@gmail.com', '1bbd886460827015e5d605ed44252251', '0987654321', 'Hà Nội', 'Hà Nội', 'Ba Đình', 'Kim Mã', NOW(), 1, NOW()),
('Trần Thị B', 'tranthib@gmail.com', '1bbd886460827015e5d605ed44252251', '0912345678', 'Hồ Chí Minh', 'Hồ Chí Minh', 'Quận 1', 'Bến Nghé', NOW(), 0, NULL),
('Lê Văn C', 'levanc@gmail.com', '1bbd886460827015e5d605ed44252251', '0908765432', 'Đà Nẵng', 'Đà Nẵng', 'Hải Châu', 'Thạch Thang', NOW(), 1, NOW());


INSERT INTO `coupon` (`code`, `description`, `type`, `measure`, `value`, `min_price`, `max_value`, `start_date`, `end_date`, `usage_limit`, `used_count`, `status`, `created`) VALUES
('DISCOUNT10', 'Giảm giá 10k cho đơn giá trị từ 50k', 1, 0, 10000, 50000, NULL, NOW(), DATE_ADD(NOW(), INTERVAL 1 DAY), 100, 0, 1, UNIX_TIMESTAMP()),
('SUMMER20', 'Giảm giá 20% cho đơn hàng từ 100k', 1, 1, 20, 100000, NULL, NOW(), DATE_ADD(NOW(), INTERVAL 1 DAY), 30, 0, 1, UNIX_TIMESTAMP());

INSERT INTO `transaction` 
(`status`, `user_id`, `user_name`, `user_email`, `user_phone`, `user_address`, `user_city`, `user_district`, `user_ward`, `message`, `amount`, `payment`, `created`) VALUES
(1, 1, 'An Nhiên', 'annhien@gmail.com', '0166666666', 'Hoàng Mai - Hà Nội', 'Hà Nội', 'Hoàng Mai', 'Định Công', 'Vui lòng trao hàng đến địa chỉ trên...', '350000.00', '', 1493983674),
(1, 2, 'GoO', 'GoO@gmail.com', '01215345336', 'Hải Phòng', 'Hải Phòng', 'Ngô Quyền', 'Máy Tơ', 'GUi hang den dia chi tren', '360000.00', '', 1493983674),
(1, 1, 'Bình Nguyễn', 'binh@gmail.com', '0987654321', 'Hà Nội ', 'Hà Nội', 'Ba Đình', 'Kim Mã', 'Gửi đến địa chỉ trên', '370000.00', '', 1494083674),
(0, 3, 'Tô Nam', 'tonam@yahoo.com.vn', '098989876', 'Thủy Nguyên - Hải Phòng', 'Hải Phòng', 'Thủy Nguyên', 'Minh Đức', 'Ship đến địa chỉ vào sáng ngày 23/5', '469000.00', '', 1494283674),
(1, 1, 'GoO', 'GoO@gmail.com', '01215345336', 'Hải Phòng', 'Hải Phòng', 'Lê Chân', 'Dư Hàng', 'Ship vào sáng mai.', '70000.00', '', 1494183674),
(0, 2, 'Linh', 'ling@yahoo.com', '098798787', 'hai Phong', 'Hải Phòng', 'Hồng Bàng', 'Quang Trung', 'ship', '69000.00', '', 1494342674),
(1, 3, 'Nhi', 'nhi@test.com', '0987654321', 'Long Biên - Hà Nội', 'Hà Nội', 'Long Biên', 'Ngọc Lâm', 'Gửi hàng đến địa chỉ trên vào ngày mai', '200000.00', '', 1493983674),
(0, 1, 'VIP User', 'test@gmail.com', '1234567890', 'Hải Phòng', 'Hải Phòng', 'Lê Chân', 'An Biên', 'Ship free', '450000.00', '', 1493983674),
(0, 2, 'test', 'test@gmail.com', '1234567890', 'Hải Phòng', 'Hải Phòng', 'Hồng Bàng', 'Phạm Hồng Thái', 'TESE', '300000.00', '', 1494383674),
(0, 3, 'Nguyen An', 'khachhang1@gmail.com', '01201212222', 'Thủy Nguyên - Hải Phòng', 'Hải Phòng', 'Thủy Nguyên', 'Quảng Thanh', 'SHIP TO', '169000.00', '', 1494407353);

INSERT INTO `order` (`transaction_id`, `product_id`, `qty`, `amount`, `status`) VALUES
(1, 6, 1, '200000.00', 0),
(2, 5, 2, '1440000.00', 1),
(3, 17, 2, '780000.00', 1);
=======
INSERT INTO `user` (`name`, `email`, `password`, `phone`, `address`, `created`, `is_verified`) VALUES
(1, 'Nguyễn Văn A', 'nguyenvana@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '0987654321', 'Hà Nội', 1743750434, 0),
(2, 'Trần Thị B', 'tranthib@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '0912345678', 'Hồ Chí Minh', 1743750434, 1),
(3, 'Lê Văn C', 'levanc@gmail.com', '4a8a08f09d37b73795649038408b5f33', '0908765432', 'Đà Nẵng', 1743750434, 0),
(4, 'Stephanie Matthews', 'jonathanmontoya@elliott.biz', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2501202816', 'North Michael', 1743750434, 0),
(5, 'Karen Hall', 'marie18@sanchez-davis.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '5067144803', 'New Jonathan', 1743750434, 1),
(6, 'Omar Gomez', 'traviswright@white.org', '7c4a8d09ca3762af61e59520943dc26494f8941b', '9618542818', 'Port Lisaberg', 1743750434, 0),
(7, 'Jacqueline Carroll', 'palmerdylan@yahoo.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2200174914', 'Alexisburgh', 1743750434, 1),
(8, 'Joseph English', 'glenda46@huynh-gibson.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '6723180749', 'Lopezton', 1743750434, 1),
(9, 'Christopher Haney', 'allenkenneth@leon-smith.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '7169485518', 'East Danielle', 1743750434, 1),
(10, 'Yvonne Mayer', 'mhenderson@roberts-rivera.biz', '7c4a8d09ca3762af61e59520943dc26494f8941b', '5816556391', 'Alexandrastad', 1743750434, 1),
(11, 'Jeffrey Davis', 'chelseadavis@whitaker.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '3451025878', 'South Kaylashire', 1743750434, 1),
(12, 'Christian Collins', 'cassandrawolfe@lee-vazquez.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '9031739408', 'Martinmouth', 1743750434, 0),
(13, 'Claire Sanchez', 'crichardson@hotmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '4908353827', 'South Tylerbury', 1743750434, 1),
(14, 'Lauren Hall', 'jasminekennedy@chandler.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '4949037784', 'Lake Annburgh', 1743750434, 1),
(15, 'Alexis Morales', 'stacyenglish@phillips.org', '7c4a8d09ca3762af61e59520943dc26494f8941b', '1947605911', 'West Crystal', 1743750434, 1),
(16, 'Eric Owens', 'maureen47@hotmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '5807593369', 'Munozside', 1743750434, 0),
(17, 'Donna Cross', 'kennethcraig@johnson.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '3706366610', 'East Mary', 1743750434, 1),
(18, 'Charles Ruiz', 'morrisonchristopher@williams.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '4460699992', 'North Justinmouth', 1743750434, 0),
(19, 'Ronald Mcdaniel', 'gonzalezsamantha@yahoo.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '6639153410', 'Longshire', 1743750434, 1),
(20, 'Kelly Rocha', 'ccurtis@fernandez.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '5395888187', 'Lake Darlene', 1743750434, 1),
(21, 'Andrew Smith', 'wandamaynard@thornton.biz', '7c4a8d09ca3762af61e59520943dc26494f8941b', '5005857924', 'Stanleyburgh', 1743750434, 1),
(22, 'Deborah Roth', 'melissa67@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '0683074156', 'Boonehaven', 1743750434, 0),
(23, 'Keith Evans', 'erictorres@hotmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '3671303360', 'Bobbyview', 1743750434, 1),
(24, 'Frank White', 'tjones@hotmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '9877351981', 'Sierratown', 1743750434, 1),
(25, 'Thomas Vaughn', 'murphymelissa@dorsey.net', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2261945984', 'Kennedyside', 1743750434, 0),
(26, 'Beth Reynolds', 'dixonlisa@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '7485944515', 'Alecton', 1743750434, 1),
(27, 'Robert Barnes', 'hodgejennifer@bowman.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '3749529787', 'Port Robertburgh', 1743750434, 0),
(28, 'James Hurley', 'kimberly94@yahoo.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '0414078823', 'New Toddhaven', 1743750434, 1),
(29, 'Caitlin Washington', 'njohns@smith.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '5485186432', 'Thompsonborough', 1743750434, 1),
(30, 'Ashley Burns', 'vnguyen@yahoo.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2902370359', 'East Danielton', 1743750434, 1),
(31, 'Tonya Stevenson', 'pflores@hotmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2393373876', 'Port Robertburgh', 1743750434, 1),
(32, 'Nicole Thomas', 'juliasilva@murray.org', '7c4a8d09ca3762af61e59520943dc26494f8941b', '3835053050', 'West Natashaland', 1743750434, 0),
(33, 'Christopher Webb', 'angelica46@yahoo.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '1116556168', 'Port Carolmouth', 1743750434, 1),
(34, 'Tonya Archer', 'heather52@yahoo.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2225425524', 'East Denise', 1743750434, 0),
(35, 'Emily Caldwell', 'justinguerrero@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2369592620', 'Smithfurt', 1743750434, 0),
(36, 'Jaime Rivera', 'dsanders@hotmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '1524622614', 'East Sheena', 1743750434, 0),
(37, 'Xavier Stevens', 'kevin35@perez-jackson.net', '7c4a8d09ca3762af61e59520943dc26494f8941b', '5669959394', 'Brandonton', 1743750434, 0),
(38, 'Brittany Newman MD', 'zjames@yahoo.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '8396083787', 'Jeffreyshire', 1743750434, 1),
(39, 'Natalie Castro', 'holdentyler@norton.info', '7c4a8d09ca3762af61e59520943dc26494f8941b', '8028086313', 'South Michaelhaven', 1743750434, 0),
(40, 'Dylan Chung', 'oliverbrian@rhodes.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '0437639556', 'Lake Michaelfurt', 1743750434, 0),
(41, 'Jason Harris', 'thomasrobert@hotmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '5049885844', 'Michaelland', 1743750434, 0),
(42, 'Katherine Pearson', 'susan76@wells.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '5067484728', 'Johnview', 1743750434, 0),
(43, 'Derrick White', 'acraig@yahoo.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '4757766290', 'Sanchezborough', 1743750434, 0),
(44, 'James Cunningham', 'samantha47@yahoo.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '4817082637', 'Matthewchester', 1743750434, 0),
(45, 'Thomas Ross', 'htate@clark.biz', '7c4a8d09ca3762af61e59520943dc26494f8941b', '3068747683', 'South Lisa', 1743750434, 0),
(46, 'Debra Mcdowell', 'hlittle@daniels.biz', '7c4a8d09ca3762af61e59520943dc26494f8941b', '0507805472', 'Jonesport', 1743750434, 0),
(47, 'Sophia Rogers', 'shelbydavis@jimenez.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '7602283196', 'Ericksonmouth', 1743750434, 0),
(48, 'Kyle Kelley', 'osbornebrian@kelly.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '8349412692', 'Lake Stephanie', 1743750434, 0),
(49, 'Matthew Black', 'stephensdebra@yahoo.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '6812690936', 'West Lisa', 1743750434, 1),
(50, 'Alejandra Mills', 'dylan77@weber.org', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2995133908', 'Kelleyborough', 1743750434, 1),
(51, 'Jessica Rodriguez', 'melissa46@norman-parker.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '8892702034', 'East Logan', 1743750434, 0),
(52, 'Victoria Ramirez', 'zflores@williams.biz', '7c4a8d09ca3762af61e59520943dc26494f8941b', '6776587924', 'Rodriguezshire', 1743750434, 1),
(53, 'Amber Greer', 'dayalan@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '6207077500', 'South Kevin', 1743750434, 1);

INSERT INTO `coupon` (`code`, `description`, `type`, `measure`, `value`, `min_price`, `max_value`, `start_date`, `end_date`, `usage_limit`, `used_count`, `status`, `created`) VALUES
('DISCOUNT10', 'Giảm giá 10k cho đơn giá trị từ 50k', 1, 1, 10000, 50000, NULL, NOW(), DATE_ADD(NOW(), INTERVAL 1 DAY), 100, 0, 1, UNIX_TIMESTAMP()),
('SUMMER20', 'Giảm giá 20% cho đơn hàng từ 100k', 1, 2, 20, 100000, NULL, NOW(), DATE_ADD(NOW(), INTERVAL 1 DAY), 30, 0, 1, UNIX_TIMESTAMP());

INSERT INTO `transaction` (`status`, `user_id`, `user_name`, `user_email`, `user_phone`, `user_address`, `message`, `amount`, `payment`, `created`) VALUES
(1, 1, 'An Nhiên', 'annhien@gmail.com', '0166666666', 'Hoàng Mai - Hà Nội', 'Vui lòng trao hàng đến địa chỉ trên...', '350000.00', '', 1493983674),
(1, 2, 'GoO', 'GoO@gmail.com', '01215345336', 'Hải Phòng', 'GUi hang den dia chi tren', '360000.00', '', 1493983674),
(1, 1, 'Bình Nguyễn', 'binh@gmail.com', '0987654321', 'Hà Nội ', 'Gửi đến địa chỉ trên', '370000.00', '', 1494083674),
(0, 3, 'Tô Nam', 'tonam@yahoo.com.vn', '098989876', 'Thủy Nguyên - Hải Phòng', 'Ship đến địa chỉ vào sáng ngày 23/5', '469000.00', '', 1494283674),
(1, 1, 'GoO', 'GoO@gmail.com', '01215345336', 'Hải Phòng', 'Ship vào sáng mai.', '70000.00', '', 1494183674),
(0, 2, 'Linh', 'ling@yahoo.com', '098798787', 'hai Phong', 'ship', '69000.00', '', 1494342674),
(1, 3, 'Nhi', 'nhi@test.com', '0987654321', 'Long Biên - Hà Nội', 'Gửi hàng đến địa chỉ trên vào ngày mai', '200000.00', '', 1493983674),
(0, 1, 'VIP User', 'test@gmail.com', '1234567890', 'Hải Phòng', 'Ship free', '450000.00', '', 1493983674),
(0, 2, 'test', 'test@gmail.com', '1234567890', 'Hải Phòng', 'TESE', '300000.00', '', 1494383674),
(0, 3, 'Nguyen An', 'khachhang1@gmail.com', '01201212222', 'Thủy Nguyên - Hải Phòng', 'SHIP TO', '169000.00', '', 1494407353);

INSERT INTO `order` (`transaction_id`, `product_id`, `qty`, `amount`, `status`) VALUES
(3, 12, 1, '360000.00', 0),
(4, 7, 1, '350000.00', 0),
(9, 4, 1, '200000.00', 0),
(10, 17, 1, '450000.00', 0),
(5, 23, 1, '370000.00', 0),
(6, 25, 1, '300000.00', 0),
(8, 10, 1, '69000.00', 0),
(7, 11, 1, '70000.00', 0);
>>>>>>> 07f5f07ecc3cb82232e4892372fd45eaf4dc5a76

INSERT INTO `slider` (`name`, `image_link`, `link`, `sort_order`, `created`) VALUES
('1', 'slide1.png', 'http://localhost/webshop/phoi-ren-p4', 1, '2017-04-25 15:24:43'),
('2', 'slide2.jpg', 'http://localhost/webshop/ao-gia-dinh-ag0560-p16', 4, '2017-04-25 15:36:41'),
('3', 'slide3.jpg', 'http://localhost/webshop/phong-cach-phoi-mau-p24', 3, '2017-04-25 15:37:00');

<<<<<<< HEAD
INSERT INTO `comments` (`id`, `user_id`, `product_id`, `rate`, `comment_content`, `created`) VALUES
(1, 1, 1, 5, 'Sản phẩm rất đẹp, chất lượng tốt.', 1493983674),
(2, 2, 1, 4, 'Chất lượng sản phẩm tốt, giá cả hợp lý.', 1493983674),
(3, 3, 1, 3, 'Sản phẩm không đẹp lắm.', 1493983674);

INSERT INTO `cart` (`user_id`, `product_id`, `rowid`, `name`, `price`, `qty`, `options`, `image_link`, `created_at`, `updated_at`) VALUES
(1, 23, '37693cfc748049e45d87b8c7d8b9aacd', 'COMBO ĐẦM REN MÙA XUÂN', 370000.00, 1, '', 'combo-dam-ren-mua-xuan-cho-me-va-be-th08602-gs210-1m4G3-g4rMfx.jpg', '2025-04-16 10:34:06', '2025-04-16 10:34:06'),
(2, 23, '37693cfc748049e45d87b8c7d8b9aacd', 'COMBO ĐẦM REN MÙA XUÂN', 370000.00, 1, '', 'combo-dam-ren-mua-xuan-cho-me-va-be-th08602-gs210-1m4G3-g4rMfx.jpg', '2025-04-16 15:00:11', '2025-04-16 15:00:11'),
(3, 7, '8f14e45fceea167a5a36dedd4bea2543', 'Đầm ren tay dài tiểu thư', 350000.00, 1, '', 'Dam_ren_den_tay_dai_tieu_thu_(3).jpg', '2025-04-16 15:02:16', '2025-04-16 15:02:16'),
(1, 12, 'c20ad4d76fe97759aa27a0c99bff6710', 'Đầm maxi phối ren cao cấp', 360000.00, 1, '', 'dam-maxi-phoi-ren-cao-cap-1m4G3-QXVTv3_simg_d0daf0_800x1200_max.jpg', '2025-04-16 15:02:33', '2025-04-16 15:02:33');

INSERT INTO `shipping_fee_rules` 
(`min_distance_km`, `max_distance_km`, `min_order_amount`, `max_order_amount`, `shipping_fee`, `unit`, `note`)
VALUES
(0, 5, NULL, NULL, 0, 'VND', 'Miễn phí ship nếu khoảng cách dưới 5km'),
(NULL, NULL, 500000, NULL, 0, 'VND', 'Miễn phí ship nếu đơn hàng trên 500k'),
(5.01, 10, NULL, NULL, 20000, 'VND', 'Phí 20k nếu khoảng cách từ 5-10km'),
(10.01, 20, 200000, NULL, 15000, 'VND', 'Phí 15k nếu >10km, <20km và >200k'),
(10.01, 20, NULL, 200000, 30000, 'VND', 'Phí 30k nếu >10km, <20km và <=200k'),
(20, NULL, NULL, NULL, 1500, 'MUL', 'Phí là 1500/km nếu khoảng cách từ 20km trở lên');

INSERT INTO `shipping_tracking` (`id`, `transaction_id`, `status`, `shipping_fee_rule_id`, `created_at`, `updated_at`, `estimated_delivery`, `callback_url`) VALUES
(1, 1, 'delivered', 1, '2025-04-16 12:55:17', '2025-04-16 13:15:34', '2025-04-19', 'http://localhost/MIS-EC/webquanao/api/shipping/webhook'),
(2, 2, 'delivered', 1, '2025-04-16 13:16:24', '2025-04-16 13:16:47', '2025-04-19', 'http://localhost/MIS-EC/webquanao/api/shipping/webhook'),
(3, 3, 'delivered', 2, '2025-04-16 13:20:33', '2025-04-16 13:20:36', '2025-04-19', 'http://localhost/MIS-EC/webquanao/api/shipping/webhook'),
(4, 4, 'delivered', 2, '2025-04-16 13:20:44', '2025-04-16 13:20:47', '2025-04-19', 'http://localhost/MIS-EC/webquanao/api/shipping/webhook'),
(5, 5, 'delivered', 1, '2025-04-16 13:41:42', '2025-04-16 14:23:25', '2025-04-19', 'http://localhost/MIS-EC/webquanao/api/shipping/webhook'),
(6, 6, 'delivered', 3, '2025-04-16 14:23:40', '2025-04-16 14:24:12', '2025-04-19', 'http://localhost/MIS-EC/webquanao/api/shipping/webhook'),
(7, 7, 'delivered', 2, '2025-04-16 14:24:32', '2025-04-16 14:32:47', '2025-04-19', 'http://localhost/MIS-EC/webquanao/api/shipping/webhook'),
(8, 8, 'delivered', 2, '2025-04-16 14:32:58', '2025-04-16 14:33:04', '2025-04-19', 'http://localhost/MIS-EC/webquanao/api/shipping/webhook'),
(9, 9, 'delivered', 1, '2025-04-16 17:00:44', '2025-04-16 17:01:23', '2025-04-19', 'http://localhost/MIS-EC/webquanao/api/shipping/webhook'),
(10, 10, 'delivered', 1, '2025-04-16 17:08:30', '2025-04-16 17:09:14', '2025-04-19', 'http://localhost/MIS-EC/webquanao/api/shipping/webhook');
=======
INSERT INTO `comments` (`user_id`, `product_id`, `rate`, `comment_content`, `created`) VALUES
(1, 1, 1, 5, 'Sản phẩm rất đẹp, chất lượng tốt.', 1493983674),
(2, 2, 1, 4, 'Chất lượng sản phẩm tốt, giá cả hợp lý.', 1493983674),
(3, 3, 1, 3, 'Sản phẩm không đẹp lắm.', 1493983674),
(4, 1, 2, 5, 'Sản phẩm rất đẹp, chất lượng tốt.', 1493983674),
(5, 2, 2, 4, 'Chất lượng sản phẩm tốt, giá cả hợp lý.', 1493983674),
(6, 3, 2, 3, 'Sản phẩm không đẹp lắm.', 1493983674),
(7, 1, 3, 5, 'Sản phẩm rất đẹp, chất lượng tốt.', 1493983674),
(8, 2, 3, 4, 'Chất lượng sản phẩm tốt, giá cả hợp lý.', 1493983674),
(9, 3, 3, 3, 'Sản phẩm không đẹp lắm.', 1493983674),
(10, 1, 4, 5, 'Sản phẩm rất đẹp, chất lượng tốt.', 1493983674),
(11, 2, 4, 4, 'Chất lượng sản phẩm tốt, giá cả hợp lý.', 1493983674),
(12, 3, 4, 3, 'Sản phẩm không đẹp lắm.', 1493983674),
(13, 11, 1, 4, 'Hơi thất vọng vì form áo không đẹp.', 1744959415),
(14, 5, 1, 5, 'Sẽ mua lại lần nữa, rất đáng tiền.', 1744959415),
(15, 21, 1, 4, 'Thiết kế đơn giản nhưng tinh tế.', 1744959415),
(16, 19, 1, 4, 'Chất lượng ổn, giao đúng size đặt.', 1744959415),
(17, 18, 1, 4, 'Đóng gói cẩn thận, không bị nhăn.', 1744959415),
(18, 12, 1, 4, 'Rất thích sản phẩm này.', 1744959415),
(19, 10, 1, 5, 'Sản phẩm không giống hình cho lắm.', 1744959415),
(20, 38, 1, 5, 'Hơi thất vọng vì form áo không đẹp.', 1744959415),
(21, 4, 2, 5, 'Sản phẩm như hình, đóng gói kỹ.', 1744959415),
(22, 52, 2, 4, 'Đường may tỉ mỉ, không bị bung chỉ.', 1744959415),
(23, 14, 2, 5, 'Giá hợp lý so với chất lượng.', 1744959415),
(24, 48, 2, 4, 'Mặc rất tôn dáng.', 1744959415),
(25, 31, 2, 4, 'Size chuẩn, mặc rất vừa.', 1744959415),
(26, 42, 3, 4, 'Mặc rất tôn dáng.', 1744959415),
(27, 20, 3, 4, 'Áo rất nhẹ, phù hợp mùa hè.', 1744959415),
(28, 6, 3, 5, 'Shop phục vụ tốt, sản phẩm chất lượng.', 1744959415),
(29, 50, 3, 5, 'Màu đẹp, không bị phai.', 1744959415),
(30, 33, 3, 4, 'Áo đẹp, vải mịn.', 1744959415),
(31, 6, 4, 5, 'Form vừa vặn, mặc rất thoải mái.', 1744959415),
(32, 46, 4, 4, 'Đẹp hơn mong đợi, vải mịn mát.', 1744959415),
(33, 18, 4, 5, 'Sản phẩm như hình, đóng gói kỹ.', 1744959415),
(34, 44, 5, 5, 'Mua lần 2 vẫn rất ưng.', 1744959415),
(35, 27, 5, 4, 'Không bị co rút sau khi giặt máy.', 1744959415),
(36, 14, 5, 4, 'Sản phẩm tốt, đóng gói đẹp.', 1744959415),
(37, 51, 5, 4, 'Phù hợp để mặc đi chơi, đi làm.', 1744959415),
(38, 26, 5, 5, 'Mặc rất tôn dáng.', 1744959415),
(39, 17, 5, 5, 'Giao hàng nhanh, sản phẩm ok.', 1744959415),
(40, 39, 6, 5, 'Mặc thoáng mát, không bị nóng.', 1744959415),
(41, 18, 6, 4, 'Đường may tỉ mỉ, không bị bung chỉ.', 1744959415),
(42, 47, 6, 5, 'Đường may tỉ mỉ, không bị bung chỉ.', 1744959415),
(43, 24, 6, 5, 'Không bị nhăn sau khi giặt.', 1744959415),
(44, 7, 6, 5, 'Hơi rộng so với size nhưng đổi trả dễ.', 1744959415),
(45, 51, 6, 5, 'Mặc lên rất sang, đường may chắc chắn.', 1744959415),
(46, 6, 6, 4, 'Vừa túi tiền, chất lượng ổn.', 1744959415),
(47, 49, 6, 5, 'Vừa túi tiền, chất lượng ổn.', 1744959415),
(48, 31, 7, 5, 'Thiết kế đơn giản nhưng tinh tế.', 1744959415),
(49, 41, 7, 4, 'Vải hơi mỏng nhưng vẫn ổn.', 1744959415),
(50, 29, 7, 4, 'Sản phẩm đáng đồng tiền.', 1744959415),
(51, 27, 7, 4, 'Không thích kiểu dáng lắm.', 1744959415),
(52, 18, 7, 5, 'Shop làm việc chuyên nghiệp.', 1744959415),
(53, 12, 7, 4, 'Áo váy mềm mịn, dễ chịu.', 1744959415),
(54, 36, 7, 5, 'Shop làm việc chuyên nghiệp.', 1744959415),
(55, 37, 8, 5, 'Áo giữ form tốt, không nhăn.', 1744959415),
(56, 20, 8, 5, 'Vải hơi mỏng nhưng vẫn ổn.', 1744959415),
(57, 39, 8, 5, 'Form không đẹp như tưởng tượng.', 1744959415),
(58, 4, 8, 4, 'Đúng mô tả, giao hàng nhanh.', 1744959415),
(59, 47, 8, 4, 'Sản phẩm giao thiếu phụ kiện.', 1744959415),
(60, 11, 8, 5, 'Giao nhầm màu nhưng shop xử lý nhanh.', 1744959415),
(61, 36, 9, 4, 'Vải không nhăn, rất thích.', 1744959415),
(62, 10, 9, 5, 'Sản phẩm rất đáng yêu.', 1744959415),
(63, 44, 9, 4, 'Giá rẻ mà chất lượng ổn.', 1744959415),
(64, 23, 9, 4, 'Shop làm việc chuyên nghiệp.', 1744959415),
(65, 35, 10, 4, 'Màu sắc đẹp, form chuẩn.', 1744959415),
(66, 5, 10, 4, 'Tay áo may hơi ẩu.', 1744959415),
(67, 11, 10, 4, 'Form vừa vặn, mặc rất thoải mái.', 1744959415),
(68, 27, 10, 5, 'Áo nhẹ, dễ mặc mùa hè.', 1744959415),
(69, 23, 10, 4, 'Sản phẩm rất đáng yêu.', 1744959415),
(70, 12, 11, 5, 'Đường may tỉ mỉ, không bị bung chỉ.', 1744959415),
(71, 52, 11, 4, 'Rất thích sản phẩm này.', 1744959415),
(72, 46, 11, 5, 'Màu rất tươi, mặc lên sáng da.', 1744959415),
(73, 34, 11, 5, 'Đặt size M mà giao size L.', 1744959415),
(74, 39, 11, 5, 'Kiểu dáng trẻ trung, năng động.', 1744959415),
(75, 14, 11, 4, 'Sản phẩm không giống hình cho lắm.', 1744959415),
(76, 20, 11, 4, 'Sản phẩm không được như mong đợi.', 1744959415),
(77, 41, 12, 4, 'Sản phẩm rất đẹp, chất lượng tốt.', 1744959415),
(78, 39, 12, 4, 'Áo đẹp, vải mịn.', 1744959415),
(79, 18, 12, 4, 'Chất lượng ổn, giao đúng size đặt.', 1744959415),
(80, 6, 13, 4, 'Sản phẩm như hình, đóng gói kỹ.', 1744959415),
(81, 25, 13, 5, 'Đường may tỉ mỉ, không bị bung chỉ.', 1744959415),
(82, 8, 13, 4, 'Sản phẩm giao thiếu phụ kiện.', 1744959415),
(83, 40, 14, 4, 'Áo khá bền, giặt máy không sao.', 1744959415),
(84, 34, 14, 5, 'Size chuẩn, mặc rất vừa.', 1744959415),
(85, 19, 14, 5, 'Áo mặc thoải mái, vải thấm hút tốt.', 1744959415),
(86, 51, 14, 5, 'Không đẹp như tưởng tượng.', 1744959415),
(87, 30, 14, 4, 'Áo mặc lên form rất đẹp.', 1744959415),
(88, 16, 14, 4, 'Màu sắc đẹp, form chuẩn.', 1744959415),
(89, 10, 14, 5, 'Không đẹp như tưởng tượng.', 1744959415),
(90, 10, 15, 4, 'Không hài lòng lắm, vải hơi nóng.', 1744959415),
(91, 19, 15, 4, 'Sản phẩm như hình, đóng gói kỹ.', 1744959415),
(92, 16, 15, 5, 'Rất vừa ý, đúng như mong đợi.', 1744959415),
(93, 38, 15, 4, 'Đặt size M mà giao size L.', 1744959415),
(94, 32, 15, 4, 'Chất liệu vải tốt, kiểu dáng đẹp.', 1744959415),
(95, 38, 16, 4, 'Màu rất tươi, mặc lên sáng da.', 1744959415),
(96, 4, 16, 4, 'Sản phẩm đúng mô tả, gói hàng cẩn thận.', 1744959415),
(97, 9, 16, 5, 'Sản phẩm rất đẹp, chất lượng tốt.', 1744959415),
(98, 19, 16, 5, 'Không thích chất liệu vải lắm.', 1744959415),
(99, 14, 16, 5, 'Shop đóng gói đẹp, có cả túi đựng.', 1744959415),
(100, 30, 16, 5, 'Mua lần 2 vẫn rất ưng.', 1744959415),
(101, 35, 16, 5, 'Vải không nhăn, rất thích.', 1744959415),
(102, 34, 16, 4, 'Áo hơi ngắn so với hình.', 1744959415),
(103, 7, 17, 4, 'Vừa túi tiền, chất lượng ổn.', 1744959415),
(104, 41, 17, 5, 'Màu sắc đẹp, form chuẩn.', 1744959415),
(105, 51, 17, 4, 'Kiểu dáng hợp trend.', 1744959415),
(106, 38, 17, 5, 'Giao nhầm màu nhưng shop xử lý nhanh.', 1744959415),
(107, 14, 18, 4, 'Áo mặc lên form rất đẹp.', 1744959415),
(108, 7, 18, 4, 'Màu rất tươi, mặc lên sáng da.', 1744959415),
(109, 36, 18, 4, 'Tay áo may hơi ẩu.', 1744959415),
(110, 9, 18, 4, 'Kiểu dáng hợp trend.', 1744959415),
(111, 15, 18, 4, 'Áo mặc lên body đẹp hơn tưởng tượng.', 1744959415),
(112, 8, 18, 4, 'Dễ phối với quần jeans, chân váy.', 1744959415),
(113, 42, 18, 5, 'Không thích chất liệu vải lắm.', 1744959415),
(114, 46, 19, 5, 'Không bị nhăn sau khi giặt.', 1744959415),
(115, 49, 19, 4, 'Vải dày dặn, mặc vào mùa lạnh ổn.', 1744959415),
(116, 24, 19, 5, 'Đúng mô tả, giao hàng nhanh.', 1744959415),
(117, 19, 19, 5, 'Đổi hàng rất dễ, shop hỗ trợ tốt.', 1744959415),
(118, 4, 20, 4, 'Không quá nổi bật nhưng chất lượng ổn.', 1744959415),
(119, 33, 20, 4, 'Giao nhầm màu nhưng shop xử lý nhanh.', 1744959415),
(120, 43, 20, 5, 'Màu hơi khác hình một chút nhưng vẫn đẹp.', 1744959415),
(121, 8, 21, 5, 'Shop phục vụ tốt, sản phẩm chất lượng.', 1744959415),
(122, 19, 21, 5, 'Vải mịn, mát, không ngứa.', 1744959415),
(123, 27, 21, 4, 'Vải dày dặn, mặc vào mùa lạnh ổn.', 1744959415),
(124, 22, 21, 5, 'Áo khá bền, giặt máy không sao.', 1744959415),
(125, 14, 21, 4, 'Mặc lên rất sang, đường may chắc chắn.', 1744959415),
(126, 11, 22, 5, 'Shop đóng gói đẹp, có cả túi đựng.', 1744959415),
(127, 10, 22, 4, 'Rất thích sản phẩm này.', 1744959415),
(128, 51, 22, 5, 'Vải đẹp, không bị xù lông khi giặt.', 1744959415),
(129, 39, 22, 5, 'Giao nhầm màu nhưng shop xử lý nhanh.', 1744959415),
(130, 13, 22, 5, 'Lần đầu mua mà rất hài lòng.', 1744959415),
(131, 9, 23, 5, 'Giao hàng nhanh, sản phẩm đúng mô tả.', 1744959415),
(132, 44, 23, 4, 'Chất vải mềm, mặc thoải mái cả ngày.', 1744959415),
(133, 31, 23, 4, 'Giao hàng nhanh, sản phẩm ok.', 1744959415),
(134, 14, 24, 5, 'Đóng gói cẩn thận, không bị nhăn.', 1744959415),
(135, 51, 24, 4, 'Vải hơi mỏng nhưng vẫn ổn.', 1744959415),
(136, 32, 24, 4, 'Size chuẩn, chất vải tốt.', 1744959415),
(137, 39, 24, 4, 'Giá rẻ mà chất lượng ổn.', 1744959415),
(138, 49, 24, 4, 'Shop phục vụ tốt, sản phẩm chất lượng.', 1744959415);

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `rowid`, `name`, `price`, `qty`, `options`, `image_link`, `created_at`, `updated_at`) VALUES
(1, 9, 23, '37693cfc748049e45d87b8c7d8b9aacd', 'COMBO ĐẦM REN MÙA XUÂN', 370000.00, 1, '', 'combo-dam-ren-mua-xuan-cho-me-va-be-th08602-gs210-1m4G3-g4rMfx.jpg', '2025-04-16 10:34:06', '2025-04-16 10:34:06'),
(2, 8, 23, '37693cfc748049e45d87b8c7d8b9aacd', 'COMBO ĐẦM REN MÙA XUÂN', 370000.00, 1, '', 'combo-dam-ren-mua-xuan-cho-me-va-be-th08602-gs210-1m4G3-g4rMfx.jpg', '2025-04-16 15:00:11', '2025-04-16 15:00:11'),
(3, 9, 7, '8f14e45fceea167a5a36dedd4bea2543', 'Đầm ren tay dài tiểu thư', 350000.00, 1, '', 'Dam_ren_den_tay_dai_tieu_thu_(3).jpg', '2025-04-16 15:02:16', '2025-04-16 15:02:16'),
(4, 9, 12, 'c20ad4d76fe97759aa27a0c99bff6710', 'Đầm maxi phối ren cao cấp', 360000.00, 1, '', 'dam-maxi-phoi-ren-cao-cap-1m4G3-QXVTv3_simg_d0daf0_800x1200_max.jpg', '2025-04-16 15:02:33', '2025-04-16 15:02:33');
>>>>>>> 07f5f07ecc3cb82232e4892372fd45eaf4dc5a76
