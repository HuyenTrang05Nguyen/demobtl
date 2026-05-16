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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id(); // Khóa chính (Mã dòng yêu thích)

            // 1. CÁC CỘT KHÓA NGOẠI
            // Liên kết với bảng users (Để biết tài khoản nào bấm nút yêu thích)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Liên kết với bảng articles (Để biết họ đang lưu bài viết nào)
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');

            // 2. RÀNG BUỘC NÂNG CAO (Thầy thêm phần này để code của em chuẩn chỉnh điểm tối đa)
            // Đảm bảo 1 user không thể bấm yêu thích TRÙNG LẶP cùng 1 bài viết nhiều lần
            $table->unique(['user_id', 'article_id']);

            $table->timestamps(); // Thời gian bấm yêu thích
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};