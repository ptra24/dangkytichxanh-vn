<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

abstract class Controller
{
    /**
     * Xác minh mã passcode gửi trong Header X-Admin-Passcode.
     * Trả về thông tin vai trò (role) và tên người dùng nếu hợp lệ, ngược lại trả về null.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|null
     */
    protected function getAuthorizedUser(Request $request)
    {
        $passcode = $request->header('X-Admin-Passcode');
        
        if (!$passcode) {
            return null;
        }

        // 1. Kiểm tra mã của Admin
        $adminPasscode = config('app.admin_passcode', 'admin123');
        if ($passcode === $adminPasscode) {
            return [
                'role' => 'admin',
                'name' => 'Administrator',
                'employee' => null
            ];
        }

        // 2. Kiểm tra mã của Nhân viên đang hoạt động
        $employee = Employee::where('passcode', $passcode)
            ->where('status', 'active')
            ->first();

        if ($employee) {
            return [
                'role' => 'employee',
                'name' => $employee->name,
                'employee' => $employee
            ];
        }

        return null;
    }
}
