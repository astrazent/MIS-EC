# Sử dụng image HTTPD chính thức
FROM php:7.4-apache

# Cài đặt các thư viện cần thiết
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mysqli pdo pdo_mysql

# Kích hoạt mod_rewrite cho Apache
RUN a2enmod rewrite

# Thay đổi Apache Listen Port từ 80 -> 8080
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf && \
    sed -i 's/<VirtualHost \*:80>/<VirtualHost *:8080>/' /etc/apache2/sites-available/000-default.conf


# Thiết lập quyền cho thư mục upload
# RUN chown -R www-data:www-data /usr/local/apache2/htdocs/upload

# Khởi động Apache
CMD ["apache2-foreground"]
