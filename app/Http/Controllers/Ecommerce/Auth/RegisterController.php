<?php

namespace App\Http\Controllers\Ecommerce\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show()
    {
        return view('ecommerce.auth.register');
    }

    public function handle()
    {
        $this->validate(request(), [
            'name' => 'required|string|max:255',
            'phone' => 'required',
            'gender' => 'required',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = Customer::create([
            'name' => request()->name,
            'phone' => request()->phone,
            'gender' => request()->gender,
            'email' => request()->email,
            'password' => Hash::make(request()->password)
        ]);

        event(new Registered($user));

        auth('customer')->login($user);

        return redirect()->to(RouteServiceProvider::HOME);
    }
}
