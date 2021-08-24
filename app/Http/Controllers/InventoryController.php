<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Product;
use App\Models\ProductStock;

class InventoryController extends Controller
{
    /**
     * Menampilkan Halaman Inventory index
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::with(['stock', 'category', 'merk'])
            ->orderBy('name', 'ASC')
            ->paginate(10);
        return view('admin.inventory.index', compact('products'));
    }

    /**
     * Menampilkan Halaman Inventory Adjust
     * 
     * @return \Illuminate\View\View
     */
    public function adjust()
    {
        return view('admin.inventory.update');
    }

    /**
     * Menampilkan halaman Adjust Summary
     * 
     * @param string $key
     * 
     * @return [type]
     */
    public function adjustSummary($key)
    {
        $histories = History::with(['product'])->whereHistoryKey($key)->get();

        return view('admin.inventory.adjust-summary', compact('histories'));
    }

    /**
     * Method ini menangani Penambahan Quantity Data product pada ProductStock
     * 
     * @throws \Illuminate\Validation\ValidationException
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update()
    {
        $this->validate(request(), [
            'qty' => 'required',
            'product_id' => 'required|exists:products,id'
        ]);

        $history = \Str::random(3) . '-' . time();

        foreach (request()->product_id as $key => $value) {
            $product = ProductStock::whereProductId($value)->first();
            $qty = $product->qty + request()->qty[$key];
            $product->update(['qty' => $qty]);

            History::create([
                'history_key' => $history,
                'product_id' => $value,
                'qty' => request()->qty[$key]
            ]);
        }

        return redirect()->route('inventory.adjust-summary', $history);
    }

    /**
     * Menampilkan dengan Spesifik Halaman Riwayat Penyesuaian Barang 
     * 
     * @param Product $product
     * 
     * @return \Illuminate\View\View
     */
    public function showHistories(Product $product)
    {
        $histories = $product->histories()->get();
        return view('admin.inventory.history-adj', compact('product', 'histories'));
    }

    /**
     * Menampilkan dengan Spesifik Halaman Riwayat Penjualan Barang
     * 
     * @param Product $product
     * 
     * @return \Illuminate\View\View
     */
    public function showSales(Product $product)
    {
        return view('admin.inventory.history-sales', compact('product'));
    }

    /**
     * Mengelola datatables untuk menampiilkan riwayat penjualan product
     * 
     * @param Product $product
     * 
     * @return mixed
     */
    public function getSales(Product $product)
    {
        if (request()->ajax()) {
            $data = $product->orderDetail()->withTrashed()->latest()->get();
            return \DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y');
                })
                ->editColumn('qty', function ($row) {
                    return "-$row->qty";
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route('orders.show', $row->order->invoice) . '" class="btn btn-sm btn-info">Detail</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Mengelola datatables untuk menampilkan riwayat penyesuaian barang
     * 
     * @param Product $product
     * 
     * @return mixed
     */
    public function getHistories(Product $product)
    {
        if (request()->ajax()) {
            $data = $product->histories()->latest()->get();
            return \DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y');
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route('inventory.adjust-summary', $row->history_key) . '" class="btn btn-info btn-sm">Detail</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
