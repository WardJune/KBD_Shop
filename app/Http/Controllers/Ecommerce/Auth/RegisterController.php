<?php

namespace App\Http\Controllers\Ecommerce\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterCustomerRequest;
use App\Models\Customer;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Menampilkan Halaman Register Customer
     * 
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('ecommerce.auth.register');
    }

    /**
     * Method ini mengatasi Customer Register
     * Membuat Data Customer
     * 
     * @param RegisterCustomerRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(RegisterCustomerRequest $request)
    {
        $user = Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        event(new Registered($user));

        auth('customer')->login($user);

        return redirect()->to(RouteServiceProvider::HOME);
    }
}
