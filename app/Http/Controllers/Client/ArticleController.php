<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Article; // Gọi Model Article để tương tác với bảng articles
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    // Hàm hiển thị danh sách bài viết (Cẩm nang du lịch)
    public function index()
    {
        // Liên kết Database: Lấy tất cả bài viết mới nhất, phân trang mỗi trang 6 bài
        $articles = Article::with('category')->latest()->paginate(6);
        
        // Trả dữ liệu về giao diện (View)
        return view('client.articles.index', compact('articles'));
    }

    // Hàm xem chi tiết một bài viết (Ví dụ: khi bấm xem chi tiết trang Ẩm thực hoặc Tour)
    public function show($id)
    {
        // Liên kết Database: Tìm bài viết theo ID, nếu không thấy thì trả về trang lỗi 404
        $article = Article::with(['category', 'comments.user'])->findOrFail($id);

        // TÍNH NĂNG THEO YÊU CẦU: Tăng lượt xem bài viết mỗi khi có người click vào xem
        $article->increment('views'); 

        // Trả dữ liệu bài viết sang giao diện chi tiết
        return view('client.articles.show', compact('article'));
    }
}