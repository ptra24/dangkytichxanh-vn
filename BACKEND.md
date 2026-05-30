# 🛠️ Tài Liệu Phát Triển Backend & Database

Tài liệu này chi tiết toàn bộ hệ thống cơ sở dữ liệu, APIs, Seeders và cấu hình định tuyến vừa được phát triển để phục vụ cho các lập trình viên tiếp theo dễ dàng kế thừa và tích hợp.

---

## 🏗️ Tổng Quan Kiến Trúc Nhánh Tính Năng

Hệ thống được phát triển tách biệt trên 2 nhánh tính năng trước khi gộp vào nhánh chính `dev`:

### 1. Nhánh `feature/be-contact-api` (Lập trình viên: Thái Quang Sơn)
- **Mục tiêu:** Cung cấp API tiếp nhận yêu cầu đăng ký tư vấn tích xanh từ form gửi đi ở Frontend Vue 3.
- **Model:** [Contact.php](app/Models/Contact.php) với `$fillable` bao gồm:
  - `name`: Họ và tên khách hàng (Bắt buộc).
  - `phone`: Số điện thoại Zalo liên hệ (Bắt buộc).
  - `link`: Đường dẫn trang cá nhân hoặc Fanpage cần lên tích xanh (Bắt buộc).
  - `service`: Loại dịch vụ khách hàng chọn (Bắt buộc).
  - `email`: Địa chỉ email (Tùy chọn).
  - `message`: Lời nhắn thêm (Tùy chọn).
  - `status`: Trạng thái xử lý (Mặc định: `'pending'`).
- **Controller:** [ContactController.php](app/Http/Controllers/Api/ContactController.php) tiếp nhận request, kiểm tra dữ liệu đầu vào (Validation) chặt chẽ, ghi nhận bản ghi và trả về JSON chuẩn RESTful.

### 2. Nhánh `feature/be-blog-and-migration` (Lập trình viên: Mai Tiểu Bảo)
- **Mục tiêu:** Khởi tạo cơ sở dữ liệu, bảng migrations và cung cấp hệ thống tin tức/mẹo lên tích xanh (Blog).
- **Migrations:**
  - `contacts` table: Lưu trữ thông tin đăng ký tư vấn tích xanh.
  - `blog_posts` table: Lưu trữ thông tin bài viết với chỉ mục `slug` là duy nhất (`unique`).
- **Model:** [BlogPost.php](app/Models/BlogPost.php) với các thuộc tính: `title`, `slug`, `content`, `excerpt`, `image_url`.
- **Controller:** [BlogController.php](app/Http/Controllers/Api/BlogController.php) cung cấp API lấy danh sách bài viết sắp xếp theo mới nhất và xem chi tiết bài viết qua `slug`.
- **Seeders:** [BlogSeeder.php](database/seeders/BlogSeeder.php) khởi tạo sẵn 3 bài viết thực tế cực kỳ chất lượng chuẩn SEO về tích xanh Facebook, TikTok, Meta Verified.

---

## 🔌 Danh Sách API Endpoints

Tất cả các API được bảo vệ bằng lớp xử lý Exception tự động định dạng JSON trong `bootstrap/app.php` nhằm hạn chế tối đa việc hiển thị lỗi HTML thô của server.

| Method | Endpoint | Payload / Params | Chức năng | Trả về thành công |
| :--- | :--- | :--- | :--- | :--- |
| **POST** | `/api/contact` | `{ name, phone, link, service, email?, message? }` | Gửi yêu cầu đăng ký tư vấn từ form | `201 Created` |
| **GET** | `/api/blog` | Không | Lấy danh sách toàn bộ bài viết | `200 OK` |
| **GET** | `/api/blog/{slug}` | `{slug}` (Ví dụ: `cach-dang-ky-tich-xanh-facebook-nhanh-nhat-nam-2026`) | Xem chi tiết một bài viết | `200 OK` (hoặc `404 Not Found` nếu sai slug) |

---

## 💾 Cấu Hình Database & Chạy Migrations

Để chạy cơ sở dữ liệu (sử dụng SQLite hoặc MySQL theo thiết lập trong `.env`), vui lòng chạy các lệnh sau ở terminal:

```bash
# 1. Thực thi các migrations (tạo bảng contacts và blog_posts)
php artisan migrate

# 2. Nạp dữ liệu bài viết mẫu của Blog vào database
php artisan db:seed
```
