<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    // Hiển thị danh sách bài viết trong trang quản trị kèm theo bộ thống kê lượt xem
    public function index()
    {
        $articles = Article::with('category')->latest()->get();
        return view('admin.articles.index', compact('articles'));
    }

    // Xử lý lưu bài viết mới do Admin đăng lên (Có Validation và Upload ảnh)
    public function store(Request $request)
    {
        // YÊU CẦU KỸ THUẬT: Validation dữ liệu đầu vào
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // File ảnh tối đa 2MB
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id(); // Lưu ID của Admin đang đăng bài

        // YÊU CẦU KỸ THUẬT: Xử lý upload hình ảnh bài viết du lịch
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/articles'), $imageName);
            $data['image'] = 'uploads/articles/' . $imageName; // Lưu đường dẫn vào Database
        }

        // Chèn dữ liệu vào bảng articles trong MySQL
        Article::create($data);

        return redirect()->route('admin.articles.index')->with('success', 'Đăng bài viết cẩm nang thành công!');
    }
}