<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::orderBy('id', 'asc')->paginate('10');

        return view('admin.categories.index', compact('category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate request from form
        $category = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:categories']
        ]);

        $category['slug'] = Str::slug($request->name);

        Category::create($category);

        return redirect(route('category.index'))->with(['success' => 'The New Category has been added']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $categories = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:categories']
        ]);

        $category->update($categories);

        return redirect(route('category.index'))->with(['succcess' => 'Category has been edited']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect(route('category.index'))->with(['success' => 'The Category has been deleted']);
    }
}
