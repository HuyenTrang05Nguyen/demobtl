<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Hiển thị danh sách người dùng trong hệ thống
    public function index()
    {
        // Lấy danh sách user và phân trang
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // Thay đổi vai trò/phân quyền (Ví dụ: Thăng cấp một User thường lên làm Admin)
    public function changeRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'role' => 'required|in:admin,user' // Chỉ cho phép chọn 1 trong 2 quyền này
        ]);

        // Cập nhật quyền mới vào cột role trong bảng users
        $user->update(['role' => $request->role]);

        return back()->with('success', 'Cập nhật phân quyền người dùng thành công!');
    }

    // Xóa tài khoản người dùng vi phạm pháp luật hoặc spam
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Ngăn chặn tình trạng Admin tự xóa chính mình
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể tự xóa tài khoản quản trị của chính mình!');
        }

        // Thực hiện xóa (Laravel sẽ tự động ngắt liên kết Session của user này)
        $user->delete();

        return back()->with('success', 'Đã xóa tài khoản người dùng thành công khỏi hệ thống.');
    }
}