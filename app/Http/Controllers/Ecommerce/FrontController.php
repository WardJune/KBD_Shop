<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function getId()
    {
        if (auth('customer')->check()) {
            $id = auth('customer')->user()->id;
            return $id;
        }
        return false;
    }

    public function index()
    {
        $product = Product::latest()->take(3)->get();
        return view('ecommerce.home', compact('product'));
    }

    public function product()
    {
        $products = Product::latest()->paginate(12);
        return view('ecommerce.product', compact('products'));
    }

    public function categoryProduct($slug)
    {
        $products = Category::where('slug', $slug)->first()->products()->latest()->paginate(12);
        return view('ecommerce.product', compact('products'));
    }

    public function show(Product $product)
    {
        $wishlist = Wishlist::where('product_id', $product->id)
            ->where('customer_id', $this->getId())
            ->first();

        // dd($wishlist);
        return view('ecommerce.show', compact('product', 'wishlist'));
    }
}
