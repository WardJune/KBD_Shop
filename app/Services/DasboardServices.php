<?php

namespace App\Services;

use App\Models\{Customer, Order, Product};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DasboardServices
{
  public function getDasboardData()
  {

    $data['users'] = Customer::withTrashed()->count();
    $data['order'] = Order::count();
    $data['products'] = Product::count();
    $data['orders'] = Order::latest()->take(8)->get();

    $data['sales'] = DB::table('orders')
      ->select(DB::raw('sum(subtotal) as total'))
      ->where('deleted_at', '=', null)
      ->whereMonth('created_at', '=', Carbon::now()->format('m-Y'))
      ->first()->total;

    $data['salesLastMonth'] = DB::table('orders')
      ->select(DB::raw('sum(subtotal) as total'))
      ->where('deleted_at', '=', null)
      ->whereMonth('created_at', '=', Carbon::now()->subMonths(1)->format('m-Y'))
      ->first()->total;

    if ($data['sales'] < $data['salesLastMonth']) {
      $data['growth'] = '<span class="text-danger mr-2"><i class="fa fa-arrow-down"></i> -' .  round((($data['salesLastMonth'] - $data['sales']) / $data['salesLastMonth']) * 100, 2) . '%</span>';
    } else {
      $data['growth'] = '<span class="text-success mr-2"><i class="fa fa-arrow-up"></i>' .  abs(round((($data['salesLastMonth'] - $data['sales']) / $data['salesLastMonth']) * 100, 2)) . '%</span>';
    }
    // for Sales Chart monthly
    $data['salesChart'] = DB::table('orders')
      ->select(DB::raw("DATE_FORMAT(created_at, '%M %Y')"), DB::raw('sum(subtotal) as total'))
      ->where('deleted_at', '=', null)
      ->whereYear('created_at', '=', Carbon::now()->format('Y'))
      ->orderBy('created_at')
      ->groupBy(DB::raw("DATE_FORMAT(created_at, '%M %Y')"))
      ->pluck(DB::raw("DATE_FORMAT(created_at, '%M %Y')"), 'total');

    $data['salesChartYearly'] = DB::table('orders')
      ->select(DB::raw("DATE_FORMAT(created_at, '%Y')"), DB::raw('sum(subtotal) as total'))
      ->where('deleted_at', '=', null)
      ->orderBy('created_at')
      ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y')"))
      ->pluck(DB::raw("DATE_FORMAT(created_at, '%Y')"), 'total');

    // for User Chart
    $data['customersChart'] = DB::table('customers')
      ->select(DB::raw("DATE_FORMAT(created_at, '%M')"), DB::raw('count(*) as total'))
      ->whereYear('created_at', Carbon::now()->format('Y'))
      ->orderBy('created_at')
      ->groupBy(DB::raw("DATE_FORMAT(created_at, '%M')"))
      ->pluck('total', "DATE_FORMAT(created_at, '%M')");

    // for Top Selling Product
    $data['product'] = DB::table('order_details')
      ->join('products', 'product_id', '=', 'products.id')
      ->select('products.name', DB::raw('sum(qty) as total'))
      ->whereYear('order_details.created_at', Carbon::now()->format('Y'))
      ->groupBy('products.name')
      ->orderBy('total', 'desc')
      ->take(5)
      ->pluck('total', 'products.name');

    return $data;
  }
}
