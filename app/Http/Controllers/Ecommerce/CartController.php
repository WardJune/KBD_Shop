<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\AddressBook;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private function getId()
    {
        $id = auth('customer')->user()->id;
        return $id;
    }

    private function getCart()
    {
        $carts = Cart::where('customer_id', $this->getId());
        return $carts;
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

        dd(request()->all());
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
        $carts = $this->getCart();
        $carts->delete();

        return back();
    }

    public function checkout()
    {
        if ($this->getCart()->get()->count() > 0) {
            $addresses = AddressBook::where('customer_id', $this->getId())->get();
            $provinces = Province::latest()->get();
            $carts = $this->getCart()->get();
            $subTotal = $carts->sum(function ($cart) {
                return $cart->product->price * $cart->qty;
            });

            return view('ecommerce.checkout', compact('carts', 'subTotal', 'provinces', 'addresses'));
        }

        return redirect(route('cart.show'));
    }

    public function processCheckout()
    {
        $this->validate(request(), [
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string',
            'customer_address' => 'required|string',
            'province_id' => 'required',
            'city_id' => 'required',
            'district_id' => 'required'
        ]);
        // begin trandsaction
        DB::beginTransaction();

        try {
            $carts = $this->getCart();
            $subTotal = $carts->get()->sum(function ($cart) {
                return $cart->product->price * $cart->qty;
            });
            // create Order
            $order = Order::create([
                'invoice' => 'KBDS-' . time(),
                'customer_id' => $this->getId(),
                'customer_name' => request()->customer_name,
                'customer_phone' => request()->customer_phone,
                'customer_address' => request()->customer_address,
                'district_id' => request()->district_id,
                'subtotal' => $subTotal
            ]);

            foreach ($carts->get() as $cart) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'price' => $cart->product->price,
                    'qty' => $cart->qty,
                    'weight' => $cart->product->weight,
                ]);
            }

            $carts->delete();

            DB::commit();

            return redirect()->route('cart.finish', $order->invoice);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function checkoutFinish(Order $order)
    {
        if ($order->customer_id == $this->getId()) {
            return view('ecommerce.checkout-finish', compact('order'));
        }

        return abort(403, "I wouldn't do that if i were you.");
    }
}
