<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
ERROR - 2025-05-23 23:15:28 --> Severity: Notice --> Array to string conversion /var/www/html/system/core/Log.php 254
ERROR - 2025-05-23 23:13:53 --> Array
ERROR - 2025-05-23 23:15:28 --> Session: Unable to obtain lock for file '/var/www/html/sessions/ci_session154b8d339138a00f6a1ba27cd59b410e9b1a01c6'.
ERROR - 2025-05-23 23:15:28 --> Severity: Warning --> session_start(): Failed to read session data: user (path: /var/www/html/sessions) /var/www/html/system/libraries/Session/Session.php 143
ERROR - 2025-05-23 23:15:28 --> Severity: Notice --> Trying to get property 'id' of non-object /var/www/html/application/core/MY_Controller.php 37
ERROR - 2025-05-23 23:16:41 --> Array
(
    [0] => stdClass Object
        (
            [id] => 7
            [user_id] => 3
            [product_id] => 25
            [rowid] => 4b04a686b0ad13dce35fa99fa4161c65
            [name] => Quần short kaki nam - QKN44
            [price] => 200000.00
            [qty] => 1
            [options] => 
            [image_link] => quan-short-kaki-nam-1m4G3-sexFoa_simg_d0daf0_800x1200_max.jpg
            [created_at] => 2025-05-23 15:09:54
            [updated_at] => 2025-05-23 15:09:54
        )

)

ERROR - 2025-05-23 23:16:46 --> Array
(
    [0] => stdClass Object
        (
            [id] => 7
            [user_id] => 3
            [product_id] => 25
            [rowid] => 4b04a686b0ad13dce35fa99fa4161c65
            [name] => Quần short kaki nam - QKN44
            [price] => 200000.00
            [qty] => 1
            [options] => 
            [image_link] => quan-short-kaki-nam-1m4G3-sexFoa_simg_d0daf0_800x1200_max.jpg
            [created_at] => 2025-05-23 15:09:54
            [updated_at] => 2025-05-23 15:09:54
        )

)

ERROR - 2025-05-23 23:20:06 --> 5
ERROR - 2025-05-23 23:21:35 --> Severity: error --> Exception: Cannot use object of type stdClass as array /var/www/html/application/views/site/cart/cart_sh.php 20
ERROR - 2025-05-23 23:25:19 --> Severity: error --> Exception: Cannot use object of type stdClass as array /var/www/html/application/views/site/cart/cart_sh.php 20
ERROR - 2025-05-23 23:25:26 --> Severity: error --> Exception: Cannot use object of type stdClass as array /var/www/html/application/views/site/cart/cart_sh.php 20
ERROR - 2025-05-23 23:29:41 --> Severity: Notice --> Trying to get property 'image_link' of non-object /var/www/html/application/views/site/cart/cart_sh.php 20
ERROR - 2025-05-23 23:29:41 --> Severity: Notice --> Trying to get property 'name' of non-object /var/www/html/application/views/site/cart/cart_sh.php 21
ERROR - 2025-05-23 23:29:41 --> Severity: Notice --> Trying to get property 'qty' of non-object /var/www/html/application/views/site/cart/cart_sh.php 22
ERROR - 2025-05-23 23:29:41 --> Severity: Notice --> Trying to get property 'qty' of non-object /var/www/html/application/views/site/cart/cart_sh.php 23
ERROR - 2025-05-23 23:29:41 --> Severity: Notice --> Trying to get property 'price' of non-object /var/www/html/application/views/site/cart/cart_sh.php 23
ERROR - 2025-05-23 23:29:41 --> Severity: Notice --> Trying to get property 'image_link' of non-object /var/www/html/application/views/site/cart/cart_sh.php 20
ERROR - 2025-05-23 23:29:41 --> Severity: Notice --> Trying to get property 'name' of non-object /var/www/html/application/views/site/cart/cart_sh.php 21
ERROR - 2025-05-23 23:29:41 --> Severity: Notice --> Trying to get property 'qty' of non-object /var/www/html/application/views/site/cart/cart_sh.php 22
ERROR - 2025-05-23 23:29:41 --> Severity: Notice --> Trying to get property 'qty' of non-object /var/www/html/application/views/site/cart/cart_sh.php 23
ERROR - 2025-05-23 23:29:41 --> Severity: Notice --> Trying to get property 'price' of non-object /var/www/html/application/views/site/cart/cart_sh.php 23
ERROR - 2025-05-23 23:29:55 --> Severity: Notice --> Undefined property: Order::$cart /var/www/html/application/controllers/Order.php 445
ERROR - 2025-05-23 23:29:55 --> Severity: error --> Exception: Call to a member function contents() on null /var/www/html/application/controllers/Order.php 445
ERROR - 2025-05-23 23:29:55 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /var/www/html/system/core/Exceptions.php:271) /var/www/html/system/core/Common.php 578
ERROR - 2025-05-23 23:29:59 --> 404 Page Not Found: Well-known/appspecific
ERROR - 2025-05-23 23:38:40 --> 404 Page Not Found: Well-known/appspecific
ERROR - 2025-05-23 23:38:50 --> Severity: Notice --> Undefined property: Order::$cart /var/www/html/application/controllers/Order.php 455
ERROR - 2025-05-23 23:38:50 --> Severity: error --> Exception: Call to a member function contents() on null /var/www/html/application/controllers/Order.php 455
ERROR - 2025-05-23 23:38:50 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /var/www/html/system/core/Exceptions.php:271) /var/www/html/system/core/Common.php 578
ERROR - 2025-05-23 23:40:43 --> 404 Page Not Found: Well-known/appspecific
ERROR - 2025-05-23 23:40:53 --> Query error: Unknown column 'user_city' in 'INSERT INTO' - Invalid query: INSERT INTO `transaction` (`user_id`, `user_name`, `user_email`, `user_address`, `user_city`, `user_district`, `user_ward`, `user_phone`, `message`, `amount`, `payment`, `created`) VALUES ('3', 'Lê Văn C', 'phannguyen2300@gmail.com', 'Đà Nẵng', '01', '002', '00040', '0908765432', '', 590000, 'cash', '2025-05-23 23:40:53')
