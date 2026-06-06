<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

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
        $passcode = $request->input('passcode');

        if (!$passcode) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập mật mã.'
            ], 400);
        }

        // 1. Kiểm tra Admin
        $adminPasscode = config('app.admin_passcode', 'admin123');
        if ($passcode === $adminPasscode) {
            return response()->json([
                'success' => true,
                'role' => 'admin',
                'name' => 'Administrator'
            ]);
        }

        // 2. Kiểm tra Nhân viên
        $employee = Employee::where('passcode', $passcode)
            ->where('status', 'active')
            ->first();

        if ($employee) {
            return response()->json([
                'success' => true,
                'role' => 'employee',
                'name' => $employee->name
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Mật mã không chính xác hoặc tài khoản nhân viên đã bị vô hiệu hóa.'
        ], 401);
    }
}
