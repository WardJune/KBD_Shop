<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function getId()
    {
        $id = auth('customer')->user()->id;
        return $id;
    }
    public function show()
    {
        $carts = Cart::where('customer_id', $this->getId())->get();
        // total all the cart price
        $subTotal = $carts->sum(function ($cart) {
            return $cart->product->price * $cart->qty;
        });
        return view('ecommerce.cart', compact('carts', 'subTotal'));
    }

    public function addToCart()
    {
        $this->validate(request(), [
            'qty' => 'integer|min:1',
            'product_id' => 'required|exists:products,id'
        ]);

        $cart = Cart::where('customer_id', $this->getId())
            ->where('product_id', request()->product_id)
            ->first();

        if ($cart) {
            $qty = $cart->qty + request()->qty;
            $cart->update(['qty' => $qty]);
        } else {
            Cart::create([
                'customer_id' => $this->getId(),
                'product_id' => request()->product_id,
                'qty' => request()->qty
            ]);
        }
        if (request()->cart) {
            return redirect()->back()->with(['success' => 'ok']);
        } else {
            return redirect()->route('cart.show');
        }
    }

    public function updateCart()
    {
        $product_id = request()->product_id;
        // loop product_id
        foreach ($product_id as $key => $value) {
            // qty == 0 ? delete cart : update qty cart
            if (request()->qty[$key] == 0) {
                Cart::find(request()->cart_id[$key])->delete();
            } else {
                Cart::find(request()->cart_id[$key])->update(['qty' => request()->qty[$key]]);
            }
        }
        return back();
    }

    public function destroy(Cart $cart)
    {
        if ($cart->customer_id == $this->getId()) {
            $cart->delete();
            return back();
        } else {
            return abort(403, 'You are not supposed to do that :(');
        }
    }

    public function emptyCart()
    {
        $carts = Cart::where('customer_id', $this->getId())->delete();

        return back();
    }
}
