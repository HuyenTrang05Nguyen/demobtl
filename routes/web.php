<?php

use Illuminate\Support\Facades\Route;

// Import các Controller phía Client (Giao diện người dùng)
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ArticleController as ClientArticleController;
use App\Http\Controllers\Client\CommentController;
use App\Http\Controllers\Client\UserController as ClientUserController; // 1. THÊM DÒNG NÀY: Xử lý Profile và Yêu thích

// Import Controller xử lý Đăng nhập / Đăng ký
use App\Http\Controllers\AuthController; // 2. SỬA DÒNG NÀY: Đã đưa AuthController ra ngoài gốc (Bỏ "Auth\")

// Import các Controller phía Admin (Trang quản trị)
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\UserController as AdminUserController; // 3. SỬA DÒNG NÀY: Đặt alias để không trùng với Client
use App\Http\Controllers\Admin\CommentController as AdminCommentController;

/*
|--------------------------------------------------------------------------
| 1. KHU VỰC PUBLIC / CLIENT (Ai cũng có thể truy cập)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home'); // Trang chủ cẩm nang
Route::get('/search', [HomeController::class, 'search'])->name('search'); // Tìm kiếm bài viết
Route::get('/articles', [ClientArticleController::class, 'index'])->name('articles.index'); // Danh sách bài viết
Route::get('/articles/{id}', [ClientArticleController::class, 'show'])->name('articles.show'); // Xem chi tiết bài viết


/*
|--------------------------------------------------------------------------
| 2. KHU VỰC AUTH (Xử lý Đăng ký, Đăng nhập, Đăng xuất)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Form đăng ký và xử lý đăng ký
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Form đăng nhập và xử lý đăng nhập
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Đăng xuất (Bắt buộc phải đăng nhập mới bấm đăng xuất được)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


/*
|--------------------------------------------------------------------------
| 3. KHU VỰC USER (Yêu cầu phải ĐĂNG NHẬP THƯỜNG)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Bình luận bài viết (Phương thức POST để gửi dữ liệu lên)
    Route::post('/articles/{article_id}/comments', [CommentController::class, 'store'])->name('comments.store');
    
    // 4. SỬA 2 DÒNG DƯỚI ĐÂY: Chuyển từ FavoriteController sang ClientUserController
    // Lưu / Bỏ lưu bài viết yêu thích
    Route::post('/articles/{article_id}/favorite', [ClientUserController::class, 'toggleFavorite'])->name('favorites.toggle');
    
    // Trang cá nhân xem danh sách các bài viết đã lưu yêu thích
    Route::get('/my-favorites', [ClientUserController::class, 'profile'])->name('favorites.index');
});


/*
|--------------------------------------------------------------------------
| 4. KHU VỰC ADMIN (Yêu cầu đăng nhập + Phải có quyền ADMIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Trang chủ quản trị (Thống kê số lượng bài viết, lượt xem)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Quản lý Danh mục bài viết (Kinh nghiệm, ẩm thực, khách sạn...) -> Đầy đủ 7 hàm CRUD
    Route::resource('categories', CategoryController::class);

    // Quản lý Bài viết (Thêm, sửa, xóa, upload hình ảnh) -> Đầy đủ 7 hàm CRUD
    Route::resource('articles', AdminArticleController::class);

    // 5. SỬA 2 DÒNG DƯỚI ĐÂY: Đổi từ UserController thành AdminUserController để tránh xung đột
    // Quản lý Người dùng (Xem danh sách tài khoản, phân quyền hoặc khóa tài khoản)
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Quản lý Bình luận (Kiểm duyệt, xóa bình luận không phù hợp)
    Route::get('/comments', [AdminCommentController::class, 'index'])->name('comments.index');
    Route::delete('/comments/{id}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');
});