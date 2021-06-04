<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    
    public function addToCart(Request $request){

        // validate request
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer'
        ]);

        $carts = json_decode($request->cookie('cart'),true);

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
        $carts = json_decode(request()->cookie('cart'), true);

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
}
