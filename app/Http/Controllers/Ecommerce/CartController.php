<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Customer;
use App\Models\District;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    // private function to get cookie cart
    private function getCarts(){
        $carts = json_decode(request()->cookie('cart'), true);
        $carts = $carts != '' ? $carts:[];

        return $carts;
    }
    
    public function addToCart(Request $request){

        // validate request
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1'
        ]);

        $carts = $this->getCarts();

        // find product berdasarkan request
        $product = Product::find($request->product_id);

        // cek jika cart &/ cookie sudah ada
        if ($carts && array_key_exists($request->product_id, $carts)) {
            // if true tambahkan qty yang sudah ada
            $carts[$request->product_id]['qty'] += $request->qty;
        } else {
            // false 

            $carts[$request->product_id] =[
                'qty' => $request->qty,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'product_image' => $product->image,
            ];

        }
        $cookie = cookie('cart',json_encode($carts),2880);

        return redirect()->back()->cookie($cookie)->with(['success' => $product->name]);
    }

    public function showCart(){
        $carts = $this->getCarts();

        // subtotal price
        $subTotal = collect($carts)->sum(function($row){
            return $row['qty'] * $row['product_price'];
        });

        return view('ecommerce.cart', compact('carts', 'subTotal'));
    }

    public function updateCart(Request $request){
        $carts = json_decode(request()->cookie('cart'),true);

        // loop request product_id karena data array 
        foreach ($request->product_id as $key => $row) {
            // jika qty == 0 unset cookie
            if ($request->qty[$key] == 0) {
            //  unset cookie
                unset($carts[$row]);
            } else {
                $carts[$row]['qty'] = $request->qty[$key];
            }
        }

        // reset cookie
        $cookie = cookie('cart', json_encode($carts), 2880);

        return redirect()->back()->cookie($cookie);
    }

    public function checkout(){

        $provinces = Province::latest()->get();
        $carts = $this->getCarts();

        // total harga procuct di keranjang
        $subTotal = collect($carts)->sum(function($row){
            return $row['qty'] * $row['product_price'];
        });

        return view('ecommerce.checkout', compact('provinces', 'carts', 'subTotal'));
    }

    public function processCheckout(Request $request){

        $this->validate($request,[
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required',
            'email' => 'required|email',
            'customer_address' => 'required',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id'
        ]);

        // inisialisasi db transaction
        DB::beginTransaction();
        try {
            // check if customer data exists
            $customer = Customer::where('email', $request->email)->first();
            // check is auth check and $customer
            if (!auth()->check() && $customer) {
                return false;
            }

            $carts = $this->getCarts();
            $subTotal = collect($carts)->sum(function($row){
                return $row['qty'] * $row['product_price'];
            });

            // create new customer
            $customer = Customer::create([
                'name' => $request->customer_name,
                'email' => $request->email,
                'phone_number' => $request->customer_phone,
                'address' => $request->customer_address,
                'district_id' => $request->district_id,
                'status' => false
            ]);

            // create order
            $order = Order::create([
                'invoice' => Str::random(4) . '-' . time(),
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'district_id' => $request->district_id,
                'subtotal' => $subTotal

            ]);

            // simpan data order detail dengan looping data di cart
            foreach ($carts as $row ) {
                // find product
                $product = Product::find($row['product_id']);
                // create orderDetail
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $row['product_id'],
                    'price' => $row['product_price'],
                    'qty' => $row['qty'],
                    'weight' => $product->weight
                ]);
            }
            // commit data
            DB::commit();

            $cookie = Cookie::forget('cart');

            return redirect(route('front.finish_checkout', $order->invoice))->cookie($cookie);
        } catch (\Exception $e) {
            // jika error rollback datanya
            DB::rollback();

            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }    

    public function checkoutFinish(Order $order){
        return view('ecommerce.checkout_finish', compact('order'));
    }

// get city and district for api
    public function getCity(){
        // query data kota berdasarkan province id
        $cities = City::where('province_id', request()->province_id)->get();
        // return json
        return response()->json([
            'status' => 'success',
            'data' => $cities
        ]);
    }

    public function getDistrict(){
        $districts = District::where('city_id', request()->city_id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $districts
        ]);
    }
}
