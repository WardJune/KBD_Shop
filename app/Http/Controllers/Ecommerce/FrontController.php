<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index(){

        $product = Product::latest()->take(3)->get();
        return view('ecommerce.home',compact('product'));
    }

    public function product(){
        $products = Product::latest()->paginate(12);
        return view('ecommerce.product', compact('products'));
    }

    public function categoryProduct($slug){
        $products = Category::where('slug', $slug)->first()->products()->latest()->paginate(12);

        return view('ecommerce.product', compact('products'));
    }

    public function show(Product $product){

        return view('ecommerce.show', compact('product'));
    }

    public function verifyCustomerRegistration(Customer $customer){
        if ($customer) {
            // update data customer
            $customer->update([
                'activate_token' => null,
                'status' => 1
            ]);

            return redirect(route('customer.login'))->with(['success' => 'Verification is Successful, please login']);
        };

        return redirect(route('customer.login'))->with(['error' => 'Invalid Verification Token']);
    }
}