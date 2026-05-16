<?php

use Illuminate\Support\Facades\Route;

// Import các Controller phía Client (Giao diện người dùng)
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ArticleController as ClientArticleController;
use App\Http\Controllers\Client\CommentController;
use App\Http\Controllers\Client\UserController as ClientUserController;

// Import Controller xử lý Đăng nhập / Đăng ký
use App\Http\Controllers\AuthController;

// Import các Controller phía Admin (Trang quản trị)
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;

/*
|--------------------------------------------------------------------------
| 1. KHU VỰC PUBLIC / CLIENT (Ai cũng có thể truy cập)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search'); 
Route::get('/articles', [ClientArticleController::class, 'index'])->name('articles.index'); 
Route::get('/articles/{id}', [ClientArticleController::class, 'show'])->name('articles.show'); 

/*
|--------------------------------------------------------------------------
| 2. KHU VỰC AUTH (Xử lý Đăng ký, Đăng nhập, Đăng xuất)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| 3. KHU VỰC USER (Yêu cầu phải ĐĂNG NHẬP THƯỜNG)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Bình luận bài viết
    Route::post('/articles/{article_id}/comments', [CommentController::class, 'store'])->name('comments.store');
    
    // Yêu thích: Lưu/Bỏ lưu bài viết & Xem danh sách
    Route::post('/articles/{article_id}/favorite', [ClientUserController::class, 'toggleFavorite'])->name('favorites.toggle');
    Route::get('/my-favorites', [ClientUserController::class, 'favorites'])->name('favorites.index');

    // Profile: Xem thông tin cá nhân & Cập nhật
    Route::get('/profile', [ClientUserController::class, 'profile'])->name('user.profile');
    Route::put('/profile/update', [ClientUserController::class, 'updateProfile'])->name('user.update');
});

/*
|--------------------------------------------------------------------------
| 4. KHU VỰC ADMIN (Yêu cầu đăng nhập + Phải có quyền ADMIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Trang chủ quản trị
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Quản lý Danh mục (7 hàm CRUD)
    Route::resource('categories', CategoryController::class);

    // Quản lý Bài viết (7 hàm CRUD)
    Route::resource('articles', AdminArticleController::class);

    // Quản lý Người dùng
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Quản lý Bình luận
    Route::get('/comments', [AdminCommentController::class, 'index'])->name('comments.index');
    Route::delete('/comments/{id}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');
});