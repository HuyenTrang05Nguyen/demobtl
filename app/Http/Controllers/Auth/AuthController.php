<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Hiển thị Form Đăng nhập
    public function showLogin() {
        return view('auth.login'); 
    }

    // Xử lý Đăng nhập
    public function login(Request $request) {
        // 1. Validation kiểm tra dữ liệu đầu vào từ Form
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // 2. Kiểm tra tài khoản trong database và tạo Session đăng nhập
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Phân quyền: Nếu là admin thì chuyển vào trang Dashboard, ngược lại ra trang chủ
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Chào mừng Quản trị viên quay trở lại!');
            }
            return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
        }

        // Nếu thông tin tài khoản sai, trả về lỗi
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }

    // Hiển thị Form Đăng ký
    public function showRegister() {
        return view('auth.register');
    }

    // Xử lý Đăng ký tài khoản khách
    public function register(Request $request) {
        // Validation: Đảm bảo email duy nhất (unique) không trùng trong bảng users
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed', // confirmed yêu cầu có ô nhập lại mật khẩu trùng khớp
        ]);

        // Tạo user mới và lưu vào bảng users (Mặc định role là user)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Mã hóa bảo mật mật khẩu
            'role' => 'user' 
        ]);

        // Đăng nhập tự động ngay sau khi đăng ký thành công
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Đăng ký tài khoản thành công!');
    }

    // Xử lý Đăng xuất
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Đã đăng xuất tài khoản.');
    }
}