<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Hiển thị danh sách tất cả bình luận để kiểm duyệt
     */
    public function index()
    {
        // PHÂN TÍCH MODEL: Dùng Eager Loading gọi cùng lúc thông tin Người viết (user) 
        // và Bài viết được bình luận (article) dựa trên quan hệ N-1 trong Comment.php
        $comments = Comment::with(['user', 'article'])->latest()->paginate(15);
        
        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Xóa bình luận không phù hợp
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return back()->with('success', 'Đã xóa bình luận vi phạm chuẩn mực thành công!');
    }
}