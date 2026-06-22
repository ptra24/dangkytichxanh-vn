@echo off
chcp 65001 > nul
title Dang Ky Tich Xanh - Dev Server

echo ================================================
echo   DAU KICH XANH - CHAY DU AN LOCAL
echo ================================================
echo.

REM --- Kiem tra thu muc ---
cd /d "%~dp0"

REM --- Kiem tra .env ---
if not exist ".env" (
    echo [WARN] Khong tim thay .env, dang copy tu .env.example...
    copy ".env.example" ".env" > nul
    echo [OK]  Da tao .env
)

REM --- Kiem tra vendor ---
if not exist "vendor" (
    echo [INFO] Chua co vendor, dang chay composer install...
    composer install --no-interaction
    if errorlevel 1 (
        echo [LOI] composer install that bai! Kiem tra Composer da duoc cai dat chua.
        pause
        exit /b 1
    )
)

REM --- Kiem tra node_modules ---
if not exist "node_modules" (
    echo [INFO] Chua co node_modules, dang chay npm install...
    npm install
    if errorlevel 1 (
        echo [LOI] npm install that bai! Kiem tra Node.js da duoc cai dat chua.
        pause
        exit /b 1
    )
)

REM --- Tao APP_KEY neu chua co ---
php artisan key:generate --no-interaction 2>nul

REM --- Migrate database ---
echo [INFO] Chay migration...
php artisan migrate --no-interaction 2>nul
echo [OK]  Migration hoan tat

REM --- Tao storage link ---
php artisan storage:link --no-interaction 2>nul

REM --- Mo 2 cua so terminal rieng biet ---
echo.
echo [INFO] Khoi dong Laravel backend   -> http://127.0.0.1:8000
echo [INFO] Khoi dong Vite frontend     -> http://localhost:5173
echo.
echo ================================================
echo  Nhan CTRL+C trong moi cua so de dung server
echo ================================================
echo.

REM --- Chay Vite trong cua so moi ---
start "Vite Dev Server" cmd /k "cd /d %~dp0 && npm run dev"

REM --- Chay Laravel trong cua so hien tai ---
php artisan serve

pause
