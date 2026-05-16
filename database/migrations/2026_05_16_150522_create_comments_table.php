<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id(); // Khóa chính (Mã bình luận)

            // 1. CÁC CỘT KHÓA NGOẠI
            // Liên kết với bảng users (để biết user nào viết bình luận này)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Liên kết với bảng articles (để biết bình luận này thuộc về bài viết nào)
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');

            // 2. NỘI DUNG BÌNH LUẬN
            $table->text('content'); // Chứa nội dung bình luận (dùng 'text' vì có thể bình luận dài)

            $table->timestamps(); // Thời gian tạo bình luận
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};