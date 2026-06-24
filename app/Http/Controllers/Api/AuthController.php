<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    /**
     * Xác minh mật mã đăng nhập của Admin hoặc Nhân viên.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyPasscode(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        $passcode = $request->input('passcode');

        $throttleKey = 'login_attempts:' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã nhập sai quá nhiều lần. Vui lòng thử lại sau ' . $seconds . ' giây.'
            ], 429);
        }

        if ($username && $password) {
            // 1. Kiểm tra Admin
            $adminUsername = config('app.admin_username', 'admin');
            $adminPassword = config('app.admin_password', config('app.admin_passcode', 'admin123'));
            
            if ($username === $adminUsername && $password === $adminPassword) {
                RateLimiter::clear($throttleKey);
                return response()->json([
                    'success' => true,
                    'role' => 'admin',
                    'name' => 'Administrator',
                    'passcode' => $adminPassword
                ]);
            }

            // 2. Kiểm tra Nhân viên
            $employee = Employee::where('name', $username)
                ->where('passcode', $password)
                ->where('status', 'active')
                ->first();

            if ($employee) {
                RateLimiter::clear($throttleKey);
                return response()->json([
                    'success' => true,
                    'role' => 'employee',
                    'name' => $employee->name,
                    'passcode' => $employee->passcode
                ]);
            }
        } elseif ($passcode) {
            // Fallback cho check passcode trực tiếp (để đảm bảo tương thích ngược)
            $adminPasscode = config('app.admin_passcode', 'admin123');
            if ($passcode === $adminPasscode) {
                RateLimiter::clear($throttleKey);
                return response()->json([
                    'success' => true,
                    'role' => 'admin',
                    'name' => 'Administrator',
                    'passcode' => $adminPasscode
                ]);
            }

            $employee = Employee::where('passcode', $passcode)
                ->where('status', 'active')
                ->first();

            if ($employee) {
                RateLimiter::clear($throttleKey);
                return response()->json([
                    'success' => true,
                    'role' => 'employee',
                    'name' => $employee->name,
                    'passcode' => $employee->passcode
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.'
            ], 400);
        }

        RateLimiter::hit($throttleKey, 300); // Khóa 5 phút

        $attemptsLeft = 5 - RateLimiter::attempts($throttleKey);
        $message = 'Tên đăng nhập hoặc mật khẩu không chính xác.';
        if ($attemptsLeft > 0) {
            $message .= ' Bạn còn ' . $attemptsLeft . ' lần thử.';
        } else {
            $message = 'Bạn đã nhập sai quá nhiều lần. Vui lòng thử lại sau 5 phút.';
        }

        return response()->json([
            'success' => false,
            'message' => $message
        ], 401);
    }

    /**
     * Đổi mật khẩu Admin hoặc Nhân viên (Yêu cầu X-Admin-Reset-Key).
     */
    public function resetPassword(Request $request)
    {
        $resetKey = $request->header('X-Admin-Reset-Key');
        $expectedKey = env('ADMIN_RESET_KEY', 'ResetKey@AZ2026!');

        if (!$resetKey || $resetKey !== $expectedKey) {
            return response()->json([
                'success' => false,
                'message' => 'Mã Security Key không hợp lệ.'
            ], 403);
        }

        $username = $request->input('username');
        $newPassword = $request->input('new_password');

        if (!$username || !$newPassword) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng cung cấp username và new_password.'
            ], 400);
        }

        if ($username === 'admin') {
            // Cập nhật passcode admin trong file .env
            $this->updateEnvFile('ADMIN_PASSCODE', $newPassword);
            
            // Cập nhật cả config tạm thời
            config(['app.admin_passcode' => $newPassword]);
            config(['app.admin_password' => $newPassword]);

            // Xóa cache config
            try {
                \Illuminate\Support\Facades\Artisan::call('config:clear');
            } catch (\Exception $e) {
                // Bỏ qua lỗi trong một số môi trường
            }

            return response()->json([
                'success' => true,
                'message' => 'Thay đổi mật khẩu Admin thành công.'
            ]);
        }

        // Tìm nhân viên
        $employee = Employee::where('name', $username)->first();
        if ($employee) {
            $employee->update([
                'passcode' => $newPassword
            ]);

            return response()->json([
                'success' => true,
                'message' => "Thay đổi mật khẩu cho nhân viên {$username} thành công."
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy người dùng.'
        ], 404);
    }

    /**
     * Cập nhật file .env.
     */
    protected function updateEnvFile($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            if (strpos($content, $key . '=') !== false) {
                $content = preg_replace(
                    "/^" . $key . "=(.*)$/m",
                    $key . "=" . $value,
                    $content
                );
            } else {
                $content .= "\n" . $key . "=" . $value;
            }

            file_put_contents($path, $content);
        }
    }
}
