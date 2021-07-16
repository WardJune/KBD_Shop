<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;

class FrontController extends Controller
{
    /**
     * Get user id yang sedang Login
     * 
     * @return $id/false
     */
    private function getId()
    {
        if (auth('customer')->check()) {
            $id = auth('customer')->user()->id;
            return $id;
        }
        return false;
    }

    /**
     * Show Halaman Home pada aplikasi 
     * 
     * @var array $products  Query data Product 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::whereStatus(1)
            ->latest()
            ->take(3)
            ->get();
        return view('ecommerce.home', compact('products'));
    }

    /**
     * Show Halaman semua Product
     * 
     * @var array $products
     * @return \Illuminate\View\View
     */
    public function product()
    {
        $products = Product::with(['merk', 'category'])->whereStatus(1)->latest()->paginate(12);
        return view('ecommerce.product', compact('products'));
    }

    /**
     * Show Halaman semua Product berdasarkan Category
     * 
     * @param Category $category  Select spesifik category berdasarkan slug
     * @var array $product  Query Data product berdasarkan Category
     * @return \Illuminate\View\View
     */
    public function categoryProduct(Category $category)
    {
        $products = $category->products()->whereStatus(1)->latest()->paginate(12);
        return view('ecommerce.product', compact('products'));
    }

    /**
     * Show Halaman Product dengan spesifik
     * 
     * @param Product $product  Select spesifik product berdasarkan slug
     * @var array $whislist  Query data wishlist
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        $wishlist = Wishlist::where('product_id', $product->id)
            ->where('customer_id', $this->getId())
            ->first();

        return view('ecommerce.show', compact('product', 'wishlist'));
    }
}
