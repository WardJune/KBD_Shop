<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    private function getId()
    {
        $id = auth('customer')->user()->id;
        return $id;
    }

    public function show()
    {
        $wishlists = Wishlist::where('customer_id', $this->getId())->get();
        return view('ecommerce.wishlist', compact('wishlists'));
    }

    public function addToWishlist()
    {
        Wishlist::create([
            'customer_id' => $this->getId(),
            'product_id' => request()->product_id
        ]);

        return back()->with(['wishlist' => 'ok']);
    }

    public function destroy(Wishlist $wishlist)
    {
        if ($wishlist->customer_id == $this->getId()) {
            $wishlist->delete();
            return back();
        } else {
            abort(403, "You are not supposed to do that :(");
        }
    }
}
