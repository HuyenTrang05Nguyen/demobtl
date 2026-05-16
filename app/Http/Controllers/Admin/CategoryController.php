<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create() {
        return view('admin.categories.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|max:255|unique:categories,name',
            'description' => 'nullable'
        ]);

        Category::create($request->all());
        return redirect()->route('admin.categories.index')->with('success', 'Tạo danh mục mới thành công!');
    }

    public function edit($id) {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id) {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable'
        ]);

        $category->update($request->all());
        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy($id) {
        $category = Category::findOrFail($id);

        // Kiểm tra xem danh mục có chứa bài viết nào không trước khi xóa để tránh lỗi dữ liệu mồ côi
        if($category->articles()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục này vì đang có bài viết thuộc danh mục!');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công!');
    }
}