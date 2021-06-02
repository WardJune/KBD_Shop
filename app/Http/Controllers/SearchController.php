<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index()
    {
        $keyword = request('keyword');
        $product = Product::where("name", "like", "%$keyword%")->latest()->paginate('10');
        
        return view('admin.product.index', [
            'product' => $product,
            'category' => Category::all()
        ]);
    }
}