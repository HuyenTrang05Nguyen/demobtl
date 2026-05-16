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
        Schema::create('articles', function (Blueprint $table) {
            $table->id(); // Khóa chính (Mã bài viết)

            // 1. CÁC CỘT KHÓA NGOẠI (Liên kết bảng)
            // Cột category_id liên kết với bảng categories
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            
            // Cột user_id liên kết với bảng users (để biết ai là người đăng bài)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // 2. CÁC CỘT THÔNG TIN BÀI VIẾT
            $table->string('title'); // Tiêu đề bài viết
            $table->text('content'); // Nội dung bài viết (dùng 'text' vì nội dung rất dài)
            $table->string('image')->nullable(); // Hình ảnh đại diện (cho phép trống lúc mới tạo chưa upload)
            $table->integer('views')->default(0); // Số lượt xem (mặc định ban đầu luôn là 0)

            $table->timestamps(); // Thời gian tạo và cập nhật
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};