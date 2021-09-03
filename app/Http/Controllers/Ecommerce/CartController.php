<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\AddressBook;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Province;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Get user id yang sedang Login
     * 
     * @var object $id
     * @return $id
     */
    private function getId()
    {
        $id = auth('customer')->user()->id;
        return $id;
    }

    /**
     * Get cart dari User yang sedang login
     * 
     * @var object $cart
     * @return object
     */
    private function getCart()
    {
        $cart = Cart::whereCustomerId($this->getId())->first();
        return $cart;
    }

    /**
     * Menampilkan Halaman Cart
     * 
     * @var object $cart 
     * @var string|int $subTotal  Total price dari data Cart
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $cart = $this->getCart();
        if ($cart && $cart->products->count() > 0) {
            $subTotal = $cart->products->sum(function ($product) {
                return $product->price * $product->pivot->qty;
            });
            return view('ecommerce.cart', compact('cart', 'subTotal'));
        }
        return view('ecommerce.cart', compact('cart'));
    }

    /**
     * Menambahkan item ke Cart
     * 
     * @var object $cart 
     * @var object $product     Data Product dari Cart
     * @var string|int $qty     Jumlah qty
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addToCart()
    {
        $this->validate(request(), [
            'qty' => 'integer|min:1',
            'product_id' => 'required|exists:products,id'
        ]);

        /** cek stock product */
        $product_stock = Product::whereId(request()->product_id)->first();
        if (request()->qty > $product_stock->stock->qty) {
            return back()->withErrors(['qty' => 'Maximum quantity to purchase this item is ' . $product_stock->stock->qty]);
        }

        $cart = $this->getCart();
        /** $cart ?? update/add item : buat cart  */
        if ($cart) {
            $product = $cart->products()->whereId(request('product_id'))->first();

            /** $product ?? update qty pada pivot table : attach item baru cart pada pivot table */
            if ($product) {
                $qty = $product->pivot->qty + request('qty');
                $cart->products()->updateExistingPivot(request()->product_id, ['qty' => $qty]);
            } else {
                $cart->products()->attach(request('product_id'), ['qty' => request('qty')]);
            }
        } else {
            $cart_create = Cart::create([
                'customer_id' => $this->getId(),
            ]);
            $cart_create->products()->attach(request()->product_id, ['qty' => request('qty')]);
        }

        if (request()->cart) {
            toast('Successfully Added to Cart', 'success');
            return back();
        } else {
            return redirect()->route('cart.show');
        }
    }

    /**
     * Update data Cart
     * 
     * @var array $product_id
     * @var object $cart
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCart()
    {
        $product_id = request()->product_id;
        $cart = $this->getCart();

        /** cek stock product */
        foreach ($product_id as $key => $id) {
            if (request()->qty[$key] > Product::whereId($id)->first()->stock->qty) {
                alert()->error('Some Products From Your Cart Exceed the Quantity From Stock')
                    ->autoClose(false)
                    ->showConfirmButton('Confirm', '#FA3B0F');
                return back();
            }
        }

        foreach ($product_id as $key => $value) {
            // qty == 0 ? detach single row : update qty pivot
            if (request()->qty[$key] == 0) {;
                $cart->products()->detach($value);
            } else {
                $cart->products()->updateExistingPivot($value, ['qty' => request()->qty[$key]]);
            }
        }
        return back();
    }

    /**
     * Delete Item data dari Cart
     * 
     * @var object $product
     * @param string|int $product_id
     * @return \Illuminate\Http\RedirectResponse|abort
     */
    public function destroy($product_id)
    {
        $product = $this->getCart()->products()->where('product_id', $product_id)->first();
        if ($product) {
            $this->getCart()->products()->detach($product_id);
            return back();
        } else {
            return abort(403, 'You are not supposed to do that :(');
        }
    }

    /**
     * Menghapus semua isi item pada Cart
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function emptyCart()
    {
        $this->getCart()->products()->detach();
        return back();
    }

    /**
     * Menampilkan Halaman Checkout
     *  
     * @var object $cart
     * @var object $addresses
     * @var object $provinces
     * @var string|int $subTotal
     * @var string|int $weight
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function checkout()
    {
        $cart = $this->getCart();

        if ($cart && $cart->products->count() > 0) {

            // cek stock product
            foreach ($cart->products as $product) {
                if ($product->pivot->qty > Product::whereId($product->id)->first()->stock->qty) {
                    alert()->error('Some Products From Your Cart Exceed the Quantity From Stock')
                        ->autoClose(false)
                        ->showConfirmButton('Confirm', '#FA3B0F');
                    return back();
                }
            }

            $addresses = AddressBook::whereCustomerId($this->getId())->get();
            $provinces = Province::latest()->get();
            $subTotal = $cart->products->sum(function ($product) {
                return $product->price * $product->pivot->qty;
            });
            $weight = $cart->products->sum(function ($product) {
                return $product->weight * $product->pivot->qty;
            });

            return view('ecommerce.checkout', compact('cart', 'subTotal', 'provinces', 'addresses', 'weight'));
        }

        return redirect(route('cart.show'));
    }

    /**
     * Proses checkout menggunakan Transaction 
     * Membuat data pada Order dan OrderDetail
     * Menghapus Cart dan item yang terkait 
     * 
     * @param CheckoutRequest $request
     * 
     * @var object $cart
     * @var string|int $subTotal
     * @var string[]|false $shipping
     * @var mixed $order
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws Exception|\Illuminate\Validation\ValidationException
     */
    public function processCheckout(CheckoutRequest $request)
    {
        DB::beginTransaction();

        try {
            $carts = $this->getCart();
            $subTotal = $carts->products->sum(function ($product) {
                return $product->price * $product->pivot->qty;
            });

            $shipping = explode('.', $request->courier);

            $order = Order::create([
                'invoice' => 'KBDS-' . time() . \Str::random(3),
                'customer_id' => $this->getId(),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'district_id' => $request->district_id,
                'subtotal' => $subTotal,
                'cost' => $shipping[1],
                'shipping' => "$shipping[0] $shipping[2]"
            ]);

            foreach ($carts->products as $product) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'qty' => $product->pivot->qty,
                    'weight' => $product->weight,
                ]);

                $product_qty = ($product->stock->qty - $product->pivot->qty);
                $product->stock->update([
                    'qty' => $product_qty
                ]);
            }
            $carts->products()->detach();
            $carts->delete();

            DB::commit();

            return redirect()->route('cart.finish', $order->invoice);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Menampilkan Halaman CheckoutFinish
     * 
     * @param Order $order
     * 
     * @return \Illuminate\Http\RedirectResponse|abort
     */
    public function checkoutFinish(Order $order)
    {
        if ($order->customer_id == $this->getId()) {
            return view('ecommerce.checkout-finish', compact('order'));
        }
        return abort(403, "I wouldn't do that if i were you.");
    }

    /**
     * Method API yang diambil dari API RajaOngkir
     * 
     * @api
     * 
     * @var string $url
     * @var \GuzzleHttp\Client $client
     * @var \Psr\Http\Message\ResponseInterface $response
     * 
     * @return mixed $body
     */
    public function getCourier()
    {
        $this->validate(request(), [
            'destination' => 'required',
            'weight' => 'required|integer'
        ]);

        $url = 'https://api.rajaongkir.com/starter/cost';
        $client = new Client();
        $response = $client->request('POST', $url, [
            'headers' => [
                'content-type' => 'application/x-www-form-urlencoded',
                'key' => config('api.rajaongkir_key'),
            ],
            'form_params' => [
                'origin' => 445,
                'destination' => request()->destination,
                'weight' => request()->weight,
                'courier' => 'jne'
            ]
        ]);

        $body = json_decode($response->getBody(), true);

        return $body;
    }
}
