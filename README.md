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

---

## 🛠️ Tài Liệu Phát Triển Backend & Database

Hệ thống Backend đã được triển khai hoàn tất trên 2 nhánh tính năng riêng biệt và sẵn sàng để tích hợp:

### 1. Nhánh `feature/be-contact-api` (Lập trình viên: Thái Quang Sơn)
- **Mục tiêu:** Cung cấp API tiếp nhận yêu cầu đăng ký tư vấn tích xanh từ Form trên giao diện Vue 3 Frontend.
- **Model:** [Contact.php](file:///d:/FPT/duantt/dangkytichxanh-vn/app/Models/Contact.php) với `$fillable` bao gồm:
  - `name`: Họ và tên khách hàng (Bắt buộc).
  - `phone`: Số điện thoại Zalo liên hệ (Bắt buộc).
  - `link`: Đường dẫn trang cá nhân hoặc Fanpage cần lên tích xanh (Bắt buộc).
  - `service`: Loại dịch vụ khách hàng chọn (Bắt buộc).
  - `email`: Địa chỉ email (Tùy chọn).
  - `message`: Lời nhắn thêm (Tùy chọn).
- **Controller:** [ContactController.php](file:///d:/FPT/duantt/dangkytichxanh-vn/app/Http/Controllers/Api/ContactController.php) tiếp nhận request, kiểm tra dữ liệu đầu vào (Validation) chặt chẽ, ghi nhận bản ghi với trạng thái mặc định `status = 'pending'`, và trả về JSON chuẩn RESTful.

### 2. Nhánh `feature/be-blog-and-migration` (Lập trình viên: Mai Tiểu Bảo)
- **Mục tiêu:** Cung cấp cơ sở dữ liệu mẫu, hệ thống quản lý bài viết tin tức/chia sẻ mẹo lên tích xanh.
- **Migrations:**
  - `contacts` table: Lưu trữ thông tin đăng ký tư vấn tích xanh.
  - `blog_posts` table: Lưu trữ thông tin bài viết với chỉ mục `slug` là duy nhất (`unique`).
- **Model:** [BlogPost.php](file:///d:/FPT/duantt/dangkytichxanh-vn/app/Models/BlogPost.php) với các thuộc tính: `title`, `slug`, `content`, `excerpt`, `image_url`.
- **Controller:** [BlogController.php](file:///d:/FPT/duantt/dangkytichxanh-vn/app/Http/Controllers/Api/BlogController.php) cung cấp API lấy danh sách bài viết sắp xếp theo mới nhất và xem chi tiết bài viết qua `slug`.
- **Seeders:** [BlogSeeder.php](file:///d:/FPT/duantt/dangkytichxanh-vn/database/seeders/BlogSeeder.php) khởi tạo sẵn 3 bài viết thực tế cực kỳ chất lượng chuẩn SEO.

---

## 🔌 Danh Sách API Endpoints

Tất cả các API được bảo vệ bằng lớp xử lý Exception tự động định dạng JSON trong `bootstrap/app.php` nhằm hạn chế tối đa việc hiển thị lỗi HTML thô của server.

| Method | Endpoint | Payload / Params | Chức năng | Trả về thành công |
| :--- | :--- | :--- | :--- | :--- |
| **POST** | `/api/contact` | `{ name, phone, link, service, email?, message? }` | Gửi yêu cầu đăng ký tư vấn | `201 Created` |
| **GET** | `/api/blog` | Không | Lấy danh sách toàn bộ bài viết | `200 OK` |
| **GET** | `/api/blog/{slug}` | `{slug}` (Ví dụ: `cach-dang-ky-tich-xanh-facebook-nhanh-nhat-nam-2026`) | Xem chi tiết bài viết | `200 OK` (hoặc `404 Not Found` nếu sai slug) |

---

## 💾 Cấu Hình Database & Chạy Migrations

Để chạy cơ sở dữ liệu (sử dụng SQLite hoặc MySQL theo thiết lập trong `.env`), vui lòng chạy các lệnh sau:

```bash
# Thực thi các migrations (tạo bảng contacts và blog_posts)
php artisan migrate

# Nạp dữ liệu bài viết mẫu của Blog vào database
php artisan db:seed
```

