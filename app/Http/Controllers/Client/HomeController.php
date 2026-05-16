<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Trang chủ website
    public function index() {
        // Lấy 3 bài viết có lượt xem (views) cao nhất làm bài nổi bật
        $featuredArticles = Article::with('category')->orderBy('views', 'desc')->take(3)->get();
        
        // Lấy danh sách danh mục (Ẩm thực, Tour du lịch...) để hiển thị thanh menu điều hướng
        $categories = Category::all();

        return view('client.home', compact('featuredArticles', 'categories'));
    }

    // Tính năng Tìm kiếm bài viết (Theo tiêu đề hoặc nội dung)
    public function search(Request $request) {
        $keyword = $request->input('keyword');

        // Chạy câu lệnh tìm kiếm tương đối sử dụng toán tử LIKE trong SQL
        $articles = Article::where('title', 'LIKE', "%{$keyword}%")
                            ->orWhere('content', 'LIKE', "%{$keyword}%")
                            ->with('category')
                            ->paginate(6);

        return view('client.articles.index', compact('articles', 'keyword'));
    }
}