# Sử dụng hình ảnh PHP chính thức tích hợp sẵn Apache cho môi trường Production
FROM php:8.3-apache

# Cài đặt các extension hệ thống và PHP cần thiết cho Laravel (bao gồm pgsql cho Neon)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql pgsql gd

# Bật module rewrite của Apache (Bắt buộc đối với các router của Laravel)
RUN a2enmod rewrite

# Cài đặt Composer trực tiếp từ image chính thức
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Cài đặt Node.js và NPM để build Vue.js/Vite
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Thiết lập thư mục làm việc trong container
WORKDIR /var/www/html

# Copy toàn bộ code dự án vào container
COPY . .

# Thay đổi cấu hình Apache trỏ thẳng vào thư mục public của Laravel thay vì html mặc định
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Chạy lệnh cài đặt thư viện PHP và build Vue.js qua Vite
RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

# Phân quyền thư mục lưu trữ cho Apache ghi dữ liệu log/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Mở cổng 80 công khai
EXPOSE 80

CMD ["apache2-foreground"]