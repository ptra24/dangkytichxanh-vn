<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Lấy danh sách toàn bộ nhân viên (Chỉ Admin).
     */
    public function index(Request $request)
    {
        $user = $this->getAuthorizedUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        if ($user['role'] !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $employees = Employee::orderBy('id', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $employees
        ]);
    }

    /**
     * Thêm mới một nhân viên (Chỉ Admin).
     */
    public function store(Request $request)
    {
        $user = $this->getAuthorizedUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        if ($user['role'] !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'passcode' => 'required|string|unique:employees,passcode|max:255',
            'status' => 'required|string|in:active,inactive'
        ], [
            'name.required' => 'Vui lòng nhập tên nhân viên.',
            'passcode.required' => 'Vui lòng nhập mật mã riêng cho nhân viên.',
            'passcode.unique' => 'Mật mã này đã được sử dụng bởi một nhân viên khác.',
            'status.in' => 'Trạng thái hoạt động không hợp lệ.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $employee = Employee::create([
            'name' => $request->name,
            'passcode' => $request->passcode,
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm nhân viên thành công.',
            'data' => $employee
        ], 201);
    }

    /**
     * Cập nhật thông tin nhân viên (Chỉ Admin).
     */
    public function update(Request $request, $id)
    {
        $user = $this->getAuthorizedUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        if ($user['role'] !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy nhân viên.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'passcode' => 'required|string|max:255|unique:employees,passcode,' . $id,
            'status' => 'required|string|in:active,inactive'
        ], [
            'name.required' => 'Vui lòng nhập tên nhân viên.',
            'passcode.required' => 'Vui lòng nhập mật mã.',
            'passcode.unique' => 'Mật mã này đã được sử dụng bởi một nhân viên khác.',
            'status.in' => 'Trạng thái hoạt động không hợp lệ.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $employee->update([
            'name' => $request->name,
            'passcode' => $request->passcode,
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật nhân viên thành công.',
            'data' => $employee
        ]);
    }

    /**
     * Xóa nhân viên (Chỉ Admin).
     */
    public function destroy(Request $request, $id)
    {
        $user = $this->getAuthorizedUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        if ($user['role'] !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy nhân viên.'
            ], 404);
        }

        $employee->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa nhân viên thành công.'
        ]);
    }
}
