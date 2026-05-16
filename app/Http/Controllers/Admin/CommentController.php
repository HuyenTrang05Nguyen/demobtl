<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;

class CommentController extends Controller
{
    // Hiển thị toàn bộ bình luận của website
    public function index() {
        // Eager loading lấy thông tin kèm theo của User đăng bài và nội dung bài viết tương ứng
        $comments = Comment::with(['user', 'article'])->latest()->get();
        return view('admin.comments.index', compact('comments'));
    }

    // Xóa bình luận không phù hợp
    public function destroy($id) {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->route('admin.comments.index')->with('success', 'Đã xóa bình luận thành công!');
    }
}