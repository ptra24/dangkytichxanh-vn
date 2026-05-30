# 📌 Thông Tin Hệ Thống Dự Án (dangkytichxanh-vn)

Tài liệu này tổng hợp toàn bộ kết quả tích hợp hệ thống, cơ sở dữ liệu, danh sách API và phân chia công việc của dự án đăng ký tích xanh.

---

## 🌟 Kết Quả Đạt Được 

### 1. Kết nối Frontend và Backend thành công 🟢
* **Hành động**: Đã loại bỏ hoàn toàn cơ chế giả lập biểu mẫu (mock simulation) cũ và chuyển đổi thành **gọi API thực tế (`fetch` tới `/api/contact`)** trực tiếp trong component [src/App.vue](file:///d:/FPT/duantt/dangkytichxanh-vn/src/App.vue).
* **Kết quả**: Khi khách hàng điền form đăng ký trên Landing Page, yêu cầu đăng ký được truyền tải mượt mà dưới dạng JSON trực tiếp đến máy chủ Laravel và xử lý trong thời gian thực.

### 2. Kiểm thử E2E qua giao diện thành công 💻
* **Hành động**: Đã thực hiện kiểm thử quy trình thực tế điền form trực tiếp trên Landing Page:
  * **Họ và tên**: `Nguyen Van B`
  * **Số điện thoại**: `0987654321`
  * **Dịch vụ**: `Tích Xanh Fanpage`
  * **Đường dẫn**: `facebook.com/nguyenvanb`
* **Kết quả**: Form gửi thành công nhận phản hồi `201 Created` từ Laravel, và hiển thị **popup Đăng Ký Thành Công** cá nhân hóa động.
* **Ảnh minh chứng**:
  * [Ảnh kết quả gửi thành công](file:///C:/Users/Administrator/.gemini/antigravity/brain/16316f9c-3b75-4880-930e-93b8cb1cf7c6/success_submission_1780120567060.png)

### 3. Kiểm tra lưu trữ cơ sở dữ liệu (SQLite) 🗄️
* **Hành động**: Truy vấn trực tiếp cơ sở dữ liệu SQLite cục bộ (`database/database.sqlite`) thông qua lệnh Artisan Tinker.
* **Bản ghi ghi nhận thành công**:
  ```php
  Array
  (
      [id] => 8
      [name] => Nguyen Van B
      [phone] => 0987654321
      [link] => facebook.com/nguyenvanb
      [service] => Tích Xanh Fanpage
      [status] => pending
      [created_at] => 2026-05-30 05:55:59
  )
  ```
## 🌐 2. Danh Sách API Endpoints & Routes

Tất cả các tuyến đường API được cấu hình trong `routes/api.php` và được quản lý phản hồi lỗi dưới dạng JSON đồng bộ thông qua `bootstrap/app.php`.

### 📌 API Dành Cho Khách Hàng (Tích hợp biểu mẫu Landing Page)
* **`POST /api/contact`**
  * **Mục đích**: Gửi yêu cầu đăng ký tích xanh từ form.
  * **Tham số yêu cầu (JSON)**:
    ```json
    {
      "name": "Nguyen Van B",
      "phone": "0987654321",
      "link": "facebook.com/nguyenvanb",
      "service": "Tích Xanh Fanpage"
    }
    ```
  * **Phản hồi thành công (`201 Created`)**:
    ```json
    {
      "success": true,
      "message": "Đăng ký tư vấn tích xanh thành công! Chúng tôi sẽ liên hệ trong ít phút.",
      "data": { "id": 8, "name": "Nguyen Van B", ... }
    }
    ```

### 📌 API Tin Tức (CMS Blog)
* **`GET /api/blog`**: Lấy danh sách bài đăng tin tức (trả về danh sách tóm tắt gồm tiêu đề, slug, mô tả ngắn, ảnh bìa).
* **`GET /api/blog/{slug}`**: Lấy chi tiết bài viết đầy đủ dựa vào `slug` (Ví dụ: `GET /api/blog/cach-dang-ky-tich-xanh-facebook-nhanh-nhat-nam-2026`).

### 📌 Tài Liệu API Swagger UI Tương Tác
* **`GET /swagger`** (Mở trực tiếp trên Trình duyệt)
  * **Đặc tả**: Cung cấp giao diện Swagger UI nền tối (Dark Theme) tuyệt đẹp để lập trình viên Frontend và Tester có thể kiểm thử trực quan tất cả các API chỉ với 1 cú click.

---

## 🚀 3. Hướng Dẫn Khởi Chạy & Kiểm Thử Nhanh

Thực hiện các lệnh sau tại thư mục gốc của dự án để thiết lập và khởi chạy nhanh:

```bash
# 1. Cài đặt các thư viện PHP & Node.js
composer install
npm install

# 2. Tạo file cấu hình môi trường từ mẫu
copy .env.example .env
php artisan key:generate

# 3. Tạo cơ sở dữ liệu SQLite và phân quyền
touch database/database.sqlite

# 4. Áp dụng Database Migration & Seeder dữ liệu mẫu bài viết
php artisan migrate --seed

# 5. Khởi chạy máy chủ Backend Laravel (Cổng 8000)
php artisan serve

# 6. Khởi chạy máy chủ Frontend Vite (Cổng 5173)
npm run dev
```

Sau khi chạy xong, hãy truy cập [http://127.0.0.1:8000](http://127.0.0.1:8000) để trải nghiệm ứng dụng tích hợp đầy đủ!
