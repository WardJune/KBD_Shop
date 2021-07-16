<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    /**
     * Get User id dari user yang sedang login
     * 
     * @return $id
     */
    private function getId()
    {
        $id = auth('customer')->user()->id;
        return $id;
    }

    /**
     * Show Halaman wishlist
     * 
     * @var array $wishlist
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $wishlists = Wishlist::where('customer_id', $this->getId())->get();
        return view('ecommerce.wishlist', compact('wishlists'));
    }

    /**
     * Menambahkan data product ke Wishlist
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addToWishlist()
    {
        Wishlist::create([
            'customer_id' => $this->getId(),
            'product_id' => request()->product_id
        ]);

        return back()->with(['wishlist' => 'ok']);
    }

    /**
     * Mengahapus data dari Wishlist
     * 
     * @param Wishlist $wishlist  Query data Wishlist berdasarkan id
     * @return \Illuminate\Http\RedirectResponse|abort
     * 
     * @throws\Illuminate\Validation\ValidationException
     */
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
