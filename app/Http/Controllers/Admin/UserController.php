<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    // Xem toàn bộ danh sách thành viên
    public function index() {
        // Chỉ lấy những tài khoản có role là 'user', xếp người mới đăng ký lên đầu
        $users = User::where('role', 'user')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    // Xóa tài khoản người dùng vi phạm quy chuẩn cộng đồng
    public function destroy($id) {
        $user = User::findOrFail($id);
        
        // Thực hiện xóa tài khoản, các bảng liên đới (comments, favorites) sẽ tự động xử lý theo khóa ngoại
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Đã xóa tài khoản người dùng ra khỏi hệ thống!');
    }
}