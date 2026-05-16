<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\User;
use App\Models\Comment;

class DashboardController extends Controller
{
    public function index() {
        // Thống kê tổng số lượng bằng hàm count() tính toán từ MySQL
        $totalArticles = Article::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalComments = Comment::count();

        // Tính tổng tất cả lượt xem bài viết bằng hàm sum() trên cột views
        $totalViews = Article::sum('views');

        // Lấy danh sách 5 bài viết có lượt đọc khủng nhất để hiển thị biểu đồ/bảng danh sách dữ liệu
        $topArticles = Article::orderBy('views', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('totalArticles', 'totalUsers', 'totalComments', 'totalViews', 'topArticles'));
    }
}