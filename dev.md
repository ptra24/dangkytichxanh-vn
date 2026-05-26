# 🚀 Tài Liệu Tích Hợp Nhánh `dev` - Backend & Database Complete

File này ghi nhận trạng thái tích hợp mới nhất của dự án sau khi gộp thành công mã nguồn của cả hai lập trình viên **Thái Quang Sơn** (Contact API) và **Mai Tiểu Bảo** (Blog & Migrations) vào nhánh chung `dev`.

---

## 📌 Trạng Thái Tích Hợp Hiện Tại

Nhánh `dev` đã được đồng bộ hóa hoàn toàn, xử lý triệt để xung đột và kiểm thử cục bộ (live-test) thành công 100%.

### 1. Định tuyến (`routes/api.php`)
Đã gộp định tuyến của cả 2 tính năng vào chung một file định tuyến thống nhất:
```php
Route::post('/contact', [ContactController::class, 'store']);
Route::get('/blog', [BlogController::class, 'index']);
Route::get('/blog/{slug}', [BlogController::class, 'show']);
```

### 2. Thống nhất Migration Cơ sở dữ liệu
- **Vấn đề trước đó:** Cả 2 nhánh đều định nghĩa file tạo bảng `contacts` gây xung đột trùng lặp thực thi. Bảng của Sơn thiếu trường `link` (đường dẫn cá nhân/Fanpage) mà Frontend gửi lên.
- **Giải pháp xử lý:** 
  - Tích hợp cột `link` trực tiếp vào file migration gốc của Sơn: `database/migrations/2026_05_24_103401_create_contacts_table.php`.
  - Xóa bỏ file migration trùng lặp của Bảo: `database/migrations/2026_05_26_000000_create_contacts_table.php` để đảm bảo cơ sở dữ liệu đồng nhất.
  - Bảo toàn file migration bài viết: `database/migrations/2026_05_26_000001_create_blog_posts_table.php`.

---

## ⚡ Danh Sách APIs Đã Chạy Thử Nghiệm Thành Công

| API Endpoint | HTTP Method | Mô tả chức năng | Kết quả thử nghiệm thực tế |
| :--- | :---: | :--- | :--- |
| `/api/contact` | **POST** | Gửi form đăng ký tư vấn từ UI | **201 Created** (Lưu DB + chuẩn bị gửi mail) hoặc **422** (Lỗi Validation chuẩn JSON) |
| `/api/blog` | **GET** | Lấy danh sách bài viết mới nhất | **200 OK** (Trả về danh sách 3 bài viết mẫu chuẩn SEO) |
| `/api/blog/{slug}` | **GET** | Xem chi tiết bài viết qua slug | **200 OK** (Trả về chi tiết bài viết) hoặc **404** (Không tìm thấy bài viết) |

---

## 💻 Hướng Dẫn Khởi Chạy Cho Lập Trình Viên Khác (Frontend / Backend)

Sau khi kéo (`git pull origin dev`) code mới nhất về, hãy thực hiện các bước sau để chạy thử nghiệm:

### Bước 1: Cài đặt và cập nhật thư viện PHP
```bash
composer install --ignore-platform-reqs
```

### Bước 2: Tạo môi trường và khởi tạo cơ sở dữ liệu sạch
```bash
# Tạo file môi trường nếu chưa có
cp .env.example .env
php artisan key:generate

# Chạy lại toàn bộ migrations và nạp dữ liệu bài viết mẫu
php artisan migrate:fresh --seed
```

### Bước 3: Khởi chạy Server Backend Laravel
```bash
php artisan serve --port=8000
```
Server sẽ chạy tại địa chỉ: `http://127.0.0.1:8000`.

---

## 📝 Ví Dụ Kiểm Thử Trực Tiếp (Test Payloads)

### 1. Đăng ký tư vấn dịch vụ (`POST /api/contact`):
* **Request Body (JSON):**
```json
{
  "name": "Nguyen Van A",
  "phone": "0912345678",
  "link": "https://facebook.com/koolguy",
  "service": "Tích Xanh Facebook"
}
```
* **Response (JSON):**
```json
{
  "success": true,
  "message": "Đăng ký tư vấn tích xanh thành công! Chúng tôi sẽ liên hệ với bạn trong giây lát.",
  "data": {
    "id": 1,
    "name": "Nguyen Van A",
    "phone": "0912345678",
    "link": "https://facebook.com/koolguy",
    "service": "Tích Xanh Facebook",
    "status": "pending",
    "created_at": "2026-05-26T04:07:28.000000Z",
    "updated_at": "2026-05-26T04:07:28.000000Z"
  }
}
```
