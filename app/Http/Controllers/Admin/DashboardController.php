<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman Dashboard Index
     * 
     *@return \Illuminate\View\View
     */
    public function index()
    {
        $users = Customer::withTrashed()->count();
        $order = Order::count();
        $products = Product::count();

        $orders = Order::latest()->take(8)->get();

        $sales = DB::table('orders')
            ->select(DB::raw('sum(subtotal) as total'))
            ->whereMonth('created_at', '=', Carbon::now()->format('m-Y'))
            ->first()->total;

        $salesLastMonth = DB::table('orders')
            ->select(DB::raw('sum(subtotal) as total'))
            ->whereMonth('created_at', '=', Carbon::now()->subMonths(1)->format('m-Y'))
            ->first()->total;

        if ($sales < $salesLastMonth) {
            $growth = '<span class="text-danger mr-2"><i class="fa fa-arrow-down"></i> -' .  round((($salesLastMonth - $sales) / $salesLastMonth) * 100, 2) . '%</span>';
        } else {
            $growth = '<span class="text-danger mr-2"><i class="fa fa-arrow-down"></i>' .  round((($salesLastMonth - $sales) / $salesLastMonth) * 100, 2) . '%</span>';
        }

        // for Sales Chart
        $salesChart = DB::table('orders')
            ->select(DB::raw("DATE_FORMAT(created_at, '%M %Y')"), DB::raw('sum(subtotal) as total'))
            ->whereYear('created_at', '=', Carbon::now()->format('Y'))
            ->orderBy('created_at')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%M %Y')"))
            ->pluck(DB::raw("DATE_FORMAT(created_at, '%M %Y')"), 'total');

        // for User Chart
        $customersChart = DB::table('customers')
            ->select(DB::raw("DATE_FORMAT(created_at, '%M')"), DB::raw('count(*) as total'))
            ->whereYear('created_at', Carbon::now()->format('Y'))
            ->orderBy('created_at')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%M')"))
            ->pluck('total', "DATE_FORMAT(created_at, '%M')");

        // for Top Selling Product
        $product = DB::table('order_details')
            ->join('products', 'product_id', '=', 'products.id')
            ->select('products.name', DB::raw('sum(qty) as total'))
            ->whereYear('order_details.created_at', Carbon::now()->format('Y'))
            ->groupBy('products.name')
            ->orderBy('total', 'desc')
            ->take(5)
            ->pluck('total', 'products.name');

        return view('dashboard', compact('users', 'order', 'sales', 'salesChart', 'customersChart', 'products', 'product', 'orders', 'growth'));
    }

    /**
     * Api untuk chart sales sort by monthly or yearly
     * 
     * @api
     * @return object
     */
    public function chart()
    {
        if (request()->monthly) {
            $salesChart = DB::table('orders')
                ->select(DB::raw("DATE_FORMAT(created_at, '%M %Y')"), DB::raw('sum(subtotal) as total'))
                ->whereYear('created_at', '=', Carbon::now()->format('Y'))
                ->orderBy('created_at')
                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%M %Y')"))
                ->pluck(DB::raw("DATE_FORMAT(created_at, '%M %Y')"), 'total');

            return response()->json([
                'status' => 200,
                'keys' => $salesChart->keys(),
                'values' => $salesChart->values()
            ]);
        }

        $salesChart = DB::table('orders')
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y')"), DB::raw('sum(subtotal) as total'))
            ->orderBy('created_at')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y')"))
            ->pluck(DB::raw("DATE_FORMAT(created_at, '%Y')"), 'total');

        return response()->json([
            'status' => 200,
            'keys' => $salesChart->keys(),
            'values' => $salesChart->values()
        ]);
    }
}
