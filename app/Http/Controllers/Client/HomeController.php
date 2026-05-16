<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. TÍNH NĂNG TÌM KIẾM (Yêu cầu bắt buộc trong file Word của em)
        $search = $request->input('search');
        
        // Tạo câu truy vấn cơ bản lấy kèm danh mục (Eager Loading)
        $query = Article::with('category');

        if ($search) {
            // Tìm theo tiêu đề hoặc tóm tắt bài viết
            $query->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('summary', 'LIKE', "%{$search}%");
        }

        // 2. PHÂN TÍCH LOGIC HIỂN THỊ TRANG CHỦ
        // Lấy 3 bài viết mới nhất (để đưa vào khu vực "Tin mới")
        $newArticles = (clone $query)->latest()->take(3)->get();

        // Lấy 4 bài viết có lượt xem cao nhất (để đưa vào mục "Bài viết nổi bật/Tour hot")
        $hotArticles = (clone $query)->orderBy('views', 'desc')->take(4)->get();

        // Lấy toàn bộ danh mục (Ẩm thực, Tour...) để hiện lên thanh Menu điều hướng
        $categories = Category::all();

        // Trả dữ liệu ra view trang chủ giao diện frontend
        return view('client.home', compact('newArticles', 'hotArticles', 'categories', 'search'));
    }
}