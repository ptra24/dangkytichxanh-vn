<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Đăng ký Tích Xanh VN

Dự án Đăng Ký Tích Xanh - dịch vụ tích xanh chuyên nghiệp trên các nền tảng mạng xã hội (Facebook, Instagram, TikTok, WhatsApp).

## Công nghệ sử dụng
- **Backend:** Laravel 11.x
- **Frontend:** Vue 3, Vite, Tailwind CSS v3 & v4

## Hướng dẫn chạy dự án
1. Cài đặt các gói phụ thuộc PHP:
   ```bash
   composer install
   ```
2. Cài đặt các gói phụ thuộc Node:
   ```bash
   npm install
   ```
3. Tạo file cấu hình môi trường:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Khởi chạy server phát triển Laravel:
   ```bash
   php artisan serve
   ```
5. Khởi chạy Vite dev server:
   ```bash
   npm run dev
   ```
