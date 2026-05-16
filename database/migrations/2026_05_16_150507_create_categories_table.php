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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Khóa chính (Mã danh mục)
            
            // THÊM 2 DÒNG NÀY:
            $table->string('name'); // Tên danh mục (Bắt buộc phải có)
            $table->text('description')->nullable(); // Mô tả chi tiết (nullable nghĩa là cho phép để trống nếu không có)
            
            $table->timestamps(); // Thời gian tạo và cập nhật
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};