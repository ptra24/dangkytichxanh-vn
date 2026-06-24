<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ChatController;

// Đăng ký thông tin liên hệ của khách hàng
Route::post('/contact', [ContactController::class, 'store']);
Route::get('/contacts/recent', [ContactController::class, 'getRecentContactsPublic']);

// Public Blog
Route::get('/blog', [BlogController::class, 'index']);
Route::get('/blog/{slug}', [BlogController::class, 'show']);

// Public Chat (Phía Khách hàng)
Route::get('/chat/messages', [ChatController::class, 'getMessagesForCustomer']);
Route::post('/chat/messages', [ChatController::class, 'sendMessageFromCustomer']);

// Xác minh đăng nhập bằng Passcode hoặc Username/Password (Admin / Employee)
Route::post('/admin/verify-passcode', [AuthController::class, 'verifyPasscode']);
Route::put('/admin/reset-password', [AuthController::class, 'resetPassword']);

// Quản lý thông tin đăng ký (Yêu cầu Header X-Admin-Passcode)
Route::get('/admin/contacts', [ContactController::class, 'index']);
Route::put('/admin/contacts/{id}', [ContactController::class, 'update']);
Route::delete('/admin/contacts/{id}', [ContactController::class, 'destroy']); // Phân quyền thêm trong Controller

// Quản lý Nhân viên (Yêu cầu passcode Admin)
Route::get('/admin/employees', [EmployeeController::class, 'index']);
Route::post('/admin/employees', [EmployeeController::class, 'store']);
Route::put('/admin/employees/{id}', [EmployeeController::class, 'update']);
Route::delete('/admin/employees/{id}', [EmployeeController::class, 'destroy']);

// Quản lý Chat (Yêu cầu passcode Admin / Employee)
Route::get('/admin/chats', [ChatController::class, 'getSessionsForAdmin']);
Route::get('/admin/chats/{session_id}/messages', [ChatController::class, 'getMessagesForAdmin']);
Route::post('/admin/chats/{session_id}/messages', [ChatController::class, 'sendMessageFromAdmin']);
Route::post('/admin/chats/{session_id}/read', [ChatController::class, 'markAsRead']);
