# 🌱 Đăng Ký Tích Xanh

Dự án web sử dụng **Laravel 13** + **Vue.js** + **Vite** + **Tailwind CSS** + **MySQL**.

---

## 📋 Yêu cầu hệ thống

| Công cụ | Phiên bản |
|---------|-----------|
| PHP | >= 8.2 |
| Composer | >= 2.x |
| Node.js | >= 18.x |
| npm | >= 9.x |
| MySQL | >= 8.0 (XAMPP) |

---

## ⚙️ Cài đặt

### 1. Clone project

```bash
git clone https://github.com/ptra24/dangkytichxanh-vn.git
cd dangkytichxanh-vn
```

### 2. Cài dependencies PHP

```bash
composer install
```

### 3. Cài dependencies Node.js

```bash
npm install
```

### 4. Tạo file môi trường

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Cấu hình file `.env`

```env
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tichxanh_db
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=localhost:5173,127.0.0.1:5173
SESSION_DRIVER=cookie
```

### 6. Tạo database

Mở **phpMyAdmin** tại `http://localhost/phpmyadmin` → tạo database tên `tichxanh_db`.

### 7. Chạy migration

```bash
php artisan migrate
```

---

## 🚀 Chạy project

Mở **2 terminal** chạy song song:

```bash
# Terminal 1 — Laravel server
php artisan serve
```

```bash
# Terminal 2 — Vite (Vue + Tailwind)
npm run dev
```

Truy cập: **http://127.0.0.1:8000**

---

## 🗂️ Cấu trúc thư mục chính
dangkytichxanh-vn/
├── app/
│   ├── Http/Controllers/
│   └── Models/
├── config/
│   ├── cors.php
│   └── sanctum.php
├── database/migrations/
├── resources/
│   ├── css/app.css
│   ├── js/app.js
│   └── views/
├── routes/
│   ├── api.php
│   └── web.php
├── .env
├── vite.config.js
└── tailwind.config.js

---

## 🔧 Lệnh hữu ích

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan make:controller TenController
php artisan make:model TenModel -m
npm run build
```

---

## 🛠️ Công nghệ sử dụng

- **Laravel 13** — Backend framework
- **Vue.js 3** — Frontend framework  
- **Vite** — Build tool + Hot Module Replacement
- **Tailwind CSS** — Utility-first CSS
- **Laravel Sanctum** — API authentication
- **MySQL** — Database (XAMPP)