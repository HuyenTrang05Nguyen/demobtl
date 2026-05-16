<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Lưu bình luận mới
    public function store(Request $request, $article_id) {
        $request->validate([
            'content' => 'required|max:1000',
        ]);

        // Tạo dữ liệu chèn thẳng vào bảng comments
        Comment::create([
            'user_id' => auth()->id(), // Lấy ID của người dùng đang đăng nhập thông qua Session
            'article_id' => $article_id, // Lấy từ tham số trên URL route truyền xuống
            'content' => $request->content,
        ]);

        return back()->with('success', 'Bình luận của bạn đã được đăng thành công!');
    }
}