<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Menampilkan Halaman Admin Categories
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $category = Category::orderBy('id', 'asc')->paginate('10');
        return view('admin.categories.index', compact('category'));
    }

    /**
     * Membuat Data Category
     *
     * @param Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $category = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:categories']
        ]);

        $category['slug'] = Str::slug($request->name);
        Category::create($category);

        alert()->success('Successfully Added');

        return redirect(route('category.index'));
    }

    /**
     * Update Spesifik Data Category
     *
     * @param Request $request
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */

    public function update(Request $request, Category $category)
    {
        $categories = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:categories']
        ]);
        $category->update($categories);

        alert()->success('Successfully Updated');

        return redirect(route('category.index'));
    }

    /**
     * Menghapus Spesifik Data Category
     * 
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category)
    {
        $category->delete();

        alert()->success('Successfully Deleted');

        return redirect(route('category.index'));
    }
}
