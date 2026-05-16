<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use App\Models\Article;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1. TRANG CÁ NHÂN & DANH SÁCH YÊU THÍCH
    |--------------------------------------------------------------------------
    | Route: Route::get('/my-favorites', [ClientUserController::class, 'profile'])
    */
    public function profile()
    {
        // Lấy thông tin của User đang đăng nhập từ Session
        $user = Auth::user(); 

        // Liên kết CSDL: Lấy các bài viết đã lưu của user này (Eager loading kèm category để hiển thị đẹp)
        $favoriteArticles = Favorite::where('user_id', $user->id)
                                    ->with('article.category')
                                    ->latest()
                                    ->get();

        // Trả dữ liệu qua view 'profile' để hiển thị giao diện giống file amthuc_2 hay tour_2 của em
        return view('client.user.profile', compact('user', 'favoriteArticles'));
    }


    /*
    |--------------------------------------------------------------------------
    | 2. TÍNH NĂNG THẢ TIM / BỎ THẢ TIM BÀI VIẾT (TOGGLE FAVORITE)
    |--------------------------------------------------------------------------
    | Route: Route::post('/articles/{article_id}/favorite', [ClientUserController::class, 'toggleFavorite'])
    */
    public function toggleFavorite($article_id)
    {
        // Kiểm tra xem bài viết này có tồn tại trong database không
        $article = Article::findOrFail($article_id);
        $user_id = Auth::id(); // Lấy nhanh ID người dùng đang đăng nhập

        // Chạy câu lệnh SQL kiểm tra xem user này đã từng "thả tim" bài viết này chưa
        $favorite = Favorite::where('user_id', $user_id)
                            ->where('article_id', $article_id)
                            ->first();

        if ($favorite) {
            // HÀNH ĐỘNG 1: Nếu đã lưu rồi -> Người dùng bấm lại nghĩa là muốn BỎ LƯU
            $favorite->delete();
            return back()->with('success', 'Đã xóa bài viết khỏi danh sách yêu thích của bạn.');
        } else {
            // HÀNH ĐỘNG 2: Nếu chưa lưu -> Tiến hành THÊM MỚI vào bảng favorites
            Favorite::create([
                'user_id' => $user_id,
                'article_id' => $article_id
            ]);
            return back()->with('success', 'Đã lưu bài viết vào danh sách yêu thích thành công!');
        }
    }
}