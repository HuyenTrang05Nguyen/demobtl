<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Lưu bình luận mới của người dùng
     * Route: Route::post('/articles/{article_id}/comment', [CommentController::class, 'store'])
     */
    public function store(Request $request, $article_id)
    {
        // Kiểm tra xem người dùng có viết gì không, tối thiểu 3 ký tự
        $request->validate([
            'content' => 'required|min:3|max:1000'
        ]);

        // Kiểm tra bài viết này có tồn tại thật trong DB không
        $article = Article::findOrFail($article_id);

        // PHÂN TÍCH MODEL: Tạo bản ghi Comment mới dựa trên mối quan hệ N-1
        Comment::create([
            'user_id'    => Auth::id(),        // Lấy ID của người dùng đang đăng nhập (Quan hệ với User)
            'article_id' => $article->id,      // Gắn ID của bài viết hiện tại (Quan hệ với Article)
            'content'    => $request->content, // Nội dung bình luận
        ]);

        // Quay lại trang chi tiết bài viết vừa xem kèm thông báo thành công
        return back()->with('success', 'Bình luận của bạn đã được đăng thành công!');
    }
}