<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index()
    {
        $keyword = request('keyword');
        $products = Product::with(['category', 'merk'])->where("name", "like", "%$keyword%")->latest()->paginate('10');

        return view('admin.product.index', [
            'products' => $products,
            'categories' => Category::all()
        ]);
    }
}
