<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Hiển thị danh sách toàn bộ danh mục trong trang quản trị
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    // Lưu danh mục mới do admin tạo
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|unique:categories,name|max:255',
            'description' => 'nullable|max:500'
        ]);

        Category::create($request->all());

        return back()->with('success', 'Thêm danh mục mới thành công!');
    }

    // Hiển thị form sửa danh mục
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    // Cập nhật thay đổi danh mục
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name'        => 'required|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|max:500'
        ]);

        $category->update($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    // Xóa danh mục
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // PHÂN TÍCH LOGIC CSDL: 
        // Trước khi xóa danh mục, kiểm tra xem có bài viết nào đang thuộc danh mục này không (Quan hệ 1-N)
        if ($category->articles()->count() > 0) {
            return back()->with('error', 'Không thể xóa! Danh mục này hiện đang có bài viết bên trong.');
        }

        $category->delete();
        return back()->with('success', 'Đã xóa danh mục thành công!');
    }
}