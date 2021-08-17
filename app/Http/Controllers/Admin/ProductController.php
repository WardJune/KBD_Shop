<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Jobs\ProductJob;
use App\Models\Category;
use App\Models\Merk;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\VarDumper\Cloner\Data;

class ProductController extends Controller
{
    /**
     * Menampilkan Halaman Admin Product Index
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::with(['category', 'merk'])->latest()->paginate(10);
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('admin.product.index', compact('products', 'categories'));
    }

    /**
     * Menampilkan Halaman Form Create/Add Product
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.product.create', [
            'category' => Category::all(),
            'merk' => Merk::all()
        ]);
    }

    /**
     * Method ini menambahkan Data Baru Product
     * 
     * @param ProductRequest $validate
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(ProductRequest $validate)
    {
        $product = $validate->except(['value']);

        if ($validate->hasFile('image')) {
            $file = $validate->image;
            $filename = Str::slug($validate->name) . '.' . $validate->image->extension();
            $file->storeAs('products', $filename);

            $product['image'] = 'products/' . $filename;
        }

        $product['slug'] = Str::slug($validate->name);
        $product['status'] = $validate->status;
        $product['desc'] = $validate->desc;

        $product_create = Product::create($product);

        // attach specifications data
        $value = request()->value;
        $sync_data = collect($value)->map(function ($value) {
            return ['value' => $value];
        });

        if ($sync_data) {
            $product_create->specifications()->attach($sync_data);
        }

        ProductStock::create([
            'product_id' => $product_create->id
        ]);
        return redirect(route('product.index'))->with(['success' => 'The New Product has been added']);
    }

    /**
     * Menampilkan Halman Form Untuk mengubah Spesifik Data Product
     *
     * @param  Product $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        if ($product->category_id == 1) {
            $data['size'] = DB::table('keyboard_sizes')->get();
            $data['type'] = DB::table('key_switchs')->get();
        } elseif ($product->category_id == 2) {
            $data['type'] = DB::table('keycap_types')->get();
        } else {
            $json = [
                ['name' => 'Clicky'],
                ['name' => 'Linear'],
                ['name' => 'Tactile'],
            ];
            $data['type'] = json_decode(json_encode($json));
        }

        return view('admin.product.edit', [
            'product' => $product,
            'category' => Category::get(),
            'merk' => Merk::get(),
            'data' => $data
        ]);
    }

    /**
     * Mengubah/Update Spesifik Data Prouct
     * 
     * @param ProductRequest $validate
     * @param Product $product
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(ProductRequest $validate, Product $product)
    {
        $products = $validate->except(['value']);
        if ($validate->hasFile('image')) {
            Storage::delete($product->image);
            $file = $validate->image;
            $filename = Str::slug($product->slug) . '.' . $file->extension();
            $file->storeAs('products', $filename);
            $products['image'] = 'products/' . $filename;
        } else {
            $products['image'] = $product->image;
        }

        // sync data relasi
        $value = request()->value;
        $sync_data = collect($value)->map(function ($value) {
            return ['value' => $value];
        });

        $product->specifications()->sync($sync_data);

        $products['merk_id'] = $validate->merk_id == 'null' ? null : $validate->merk_id;
        ($products['size'] ?? '') ? $products['size'] : $products['size'] = null;

        $product->update($products);

        return redirect(route('product.index'))->with(['success' => 'The Product has been updated']);
    }

    /**
     * Menghapus Spesifik Data Product
     * 
     * @param Product $product
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        Storage::delete($product->image);
        $product->stock->delete();
        $product->specifications()->detach();
        $product->delete();

        return redirect(route('product.index'))->with(['success' => 'The Product has been deleted']);
    }

    /**
     * Method ini melakukan Penginputan Data Product dalam jumlah yang besar dalama satu waktu
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function massUpload(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'file' => 'required|mimes:xlsx'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file;
            $filename = time() . '-product.' . $request->file->extension();
            $file->storeAs('uploads', $filename);

            ProductJob::dispatch($request->category_id, $filename);
            return redirect()->back()->with(['success' => 'Upload Product has been Scheduled']);
        }
    }

    public function type()
    {
        if (request()->category_id == 1) {
            $data['type'] = DB::table('key_switchs')->get();
            $data['size'] = DB::table('keyboard_sizes')->get();
        } elseif (request()->category_id == 2) {
            $data['type'] = DB::table('keycap_types')->get();
        } else {
            $data['type'] = [
                ['name' => 'Clicky'],
                ['name' => 'Linear'],
                ['name' => 'Tactile'],
            ];
        }
        return response()->json([
            'status' => '200',
            'data' => $data
        ]);
    }
}
