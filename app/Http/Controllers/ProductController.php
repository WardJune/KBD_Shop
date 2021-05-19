<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Merk;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $product = Product::latest()->paginate('10');
        return view('admin.product.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product.create', [
            'category' => Category::all(),
            'merk' => Merk::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $validate)
    {
       $product = $validate->all();
        // check apakah ada gambar
       if ($validate->hasFile('image')) {
            $file = $validate->image;
            // nama file kombinasi waktu + slug 
            $filename = Str::slug($validate->name) . '.' . $validate->image->extension();
            $file->storeAs('products', $filename);

            $product['image'] ='products/' . $filename;            
        }

        $product['slug'] = Str::slug($validate->name);
        $product['status'] = $validate->status;
        $product['type'] = null;
        $product['desc'] = $validate->desc;

        Product::create($product);
        return redirect(route('product.index'))->with(['success'=> 'The New Product has been added']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
    return view('admin.product.edit',[
        'product' => $product,
        'category' => Category::all(),
        'merk' => Merk::all()
    ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $validate, Product $product)
    {
        $products = $validate->all();
    
        if ($validate->hasFile('image')) {
            Storage::delete($product->image);
            $file = $validate->image;
            $filename = Str::slug($product->slug) . '.' . $file->extension();
            $file->storeAs('products', $filename);
            $products['image'] ='products/' . $filename;        
        } else {
            $products['image'] = $product->image;
        }
        $products['merk_id'] = $validate->merk_id == 'null' ? null : $validate->merk_id;
        $product->update($products);

        return redirect(route('product.index'))->with(['success' => 'The Product has been updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        Storage::delete($product->image);
        $product->delete();

        return redirect(route('product.index'))->with(['success' => 'The Product has been deletd']);
    }
}