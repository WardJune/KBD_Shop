<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Merk;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\DB;

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
        $popular = OrderDetail::select('product_id', DB::raw('sum(qty) as total'))
            ->with(['product' => function ($q) {
                return $q->select('id', 'name', 'slug', 'image', 'price');
            }])
            ->groupBy('product_id')
            ->orderBy('total', 'desc')
            ->take(4)
            ->get();

        $products = Product::select('name', 'slug', 'image', 'price')
            ->whereStatus(1)
            ->latest()
            ->take(3)
            ->get();
        return view('ecommerce.home', compact('products', 'popular'));
    }

    /**
     * Show Halaman semua Product
     * 
     * @var array $products
     * @return \Illuminate\View\View
     */
    /*
    public function product()
    {
        $products = Product::with(['merk', 'category', 'stock'])->whereStatus(1);
        $data = request()->all();

        if (request()->sort) {
            if (request()->sort == 'name-asc') {
                $products = $products->orderBy('name', 'asc');
                $data['sort'] = request()->sort;
            } elseif (request()->sort == 'name-desc') {
                $products = $products->orderBy('name', 'desc');
                $data['sort'] = request()->sort;
            } elseif (request()->sort == 'price-asc') {
                $products = $products->orderBy('price', 'asc');
                $data['sort'] = request()->sort;
            } elseif (request()->sort == 'price-desc') {
                $products = $products->orderBy('price', 'desc');
                $data['sort'] = request()->sort;
            }
        }

        if (request()->merk) {
            if (request()->merk == request()->merk) {
                $products = $products->whereMerkId(request()->merk);
                $data['merk'] = request()->merk;
            }
        }

        $products = $products->paginate(12);
        return view('ecommerce.product', compact('products', 'data'));
    } */

    /**
     * Menampilkan Halaman semua Product berdasarkan Category beserta filter
     * 
     * @param mixed $slug
     * 
     * @var array $product  Query Data product berdasarkan Category
     * @return \Illuminate\View\View
     */
    public function categoryProduct($slug)
    {
        $products = Category::where('slug', $slug)
            ->firstOrFail()
            ->products()
            ->whereStatus(1)
            ->with(['stock' => function ($q) {
                return $q->select('qty', 'product_id');
            }])
            ->whereStatus(1);

        $data = request()->all();

        // filter order by
        if (request()->sort) {
            if (request()->sort == 'name-asc') {
                $products = $products->orderBy('name', 'asc');
                $data['sort'] = request()->sort;
            } elseif (request()->sort == 'name-desc') {
                $products = $products->orderBy('name', 'desc');
                $data['sort'] = request()->sort;
            } elseif (request()->sort == 'price-asc') {
                $products = $products->orderBy('price', 'asc');
                $data['sort'] = request()->sort;
            } elseif (request()->sort == 'price-desc') {
                $products = $products->orderBy('price', 'desc');
                $data['sort'] = request()->sort;
            }
        }

        // filter by merk
        if (request()->merk) {
            $products = $products->whereMerkId(request()->merk);
            $data['merk'] = request()->merk;
        }

        // filter by size keyboard
        if (request()->size) {
            $size = DB::table('keyboard_sizes')->whereId(request()->size)->first();
            if ($size) {
                $products = $products->whereSize($size->name);
                $data['size'] = request()->size;
            } else {
                abort(404);
            }
        }

        // filter by switch
        if (request()->switch) {
            $switch = DB::table('key_switchs')->whereId(request()->switch)->first();
            if ($switch) {
                $products = $products->whereType($switch->name);
            } else {
                abort(404);
            }
        }

        if ($slug == 'switch') {
            // filter switch by type
            if (request()->type) {
                if (request()->type == 1) {
                    $products = $products->whereType('Clicky');
                    $data['type'] = request()->type;
                } elseif (request()->type == 2) {
                    $products = $products->whereType('Linear');
                    $data['type'] = request()->type;
                } elseif (request()->type == 3) {
                    $products = $products->whereType('Tactile');
                    $data['type'] = request()->type;
                }
            }
        } elseif ($slug == 'keycaps') {
            // filter keycap by type
            if (request()->type_k) {
                $keycap = DB::table('keycap_types')->whereId(request()->type_k)->first();
                if ($keycap) {
                    $products = $products->whereType($keycap->name);
                } else {
                    abort(404);
                }
            }
        }

        $products = $products->paginate(12);
        $slugs = $slug;
        return view('ecommerce.product', compact('products', 'slugs', 'data'));
    }

    /**
     * Show Halaman Product dengan spesifik beserta product lain dari product tsb yang serupa
     * 
     * @param Product $product  Select spesifik product berdasarkan slug
     * @var array $whislist  Query data wishlist
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        if ($product->status == 0) {
            abort(404);
        }

        $wishlist = Wishlist::where('product_id', $product->id)
            ->where('customer_id', $this->getId())
            ->first();

        // related Product
        $related = Product::select('name', 'slug', 'image', 'price')
            ->whereStatus(1)
            ->whereHas('category', function ($q) use ($product) {
                $q->where('name', $product->category->name);
            })
            ->whereNotIn('name', [$product->name])
            ->take(4)
            ->get();

        return view('ecommerce.show', compact('product', 'wishlist', 'related'));
    }

    /**
     * Menampilkan halaman search
     * 
     * @return \Illuminate\View\View
     */
    public function search()
    {
        return view('ecommerce.search-result');
    }
}
