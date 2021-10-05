<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Jobs\ProductJob;
use App\Models\{Category, Images, Merk, Product};
use App\Services\ProductServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Storage};

class ProductController extends Controller
{
    protected $productServices;

    public function __construct(ProductServices $productServices)
    {
        $this->productServices = $productServices;
    }
    /**
     * Menampilkan Halaman Admin Product Index
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $products = $this->productServices->getProducts(request()->keyword);

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
            'category' => Category::get(),
            'merk' => Merk::get()
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
        if ($this->productServices->storeProduct($validate)) {
            return redirect(route('product.index'))->withToastSuccess('Succesfully Added');
        } else {
            return back()->withToastError('Error Happend');
        }
    }

    /**
     * Menampilkan Halman Form Untuk mengubah Spesifik Data Product
     *
     * @param  Product $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        $data = $this->productServices->editData($product->category_id);

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
        $this->productServices->updateProduct($validate, $product);

        return redirect(route('product.index'))->withToastSuccess('Successfully Updated');
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
        if ($this->productServices->destroyProduct($product)) {
            return redirect(route('product.index'))->withToastSuccess('Succesfully Deleted');
        } else {
            return redirect(route('product.index'))->withToastError('Error Happend');
        }
    }

    public function destroyImage(Images $images)
    {
        Storage::delete($images->name);
        $images->delete();

        return back()->withToastSuccess('Succesfully Deleted');
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
            $filename = time() . '-product.' . $file->extension();
            $file->storeAs('uploads', $filename);

            ProductJob::dispatch($request->category_id, $filename);
            return redirect()->back()->withToastSuccess('Upload Product has been Scheduled');
        }
    }

    /**
     * @return [type]
     */
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
