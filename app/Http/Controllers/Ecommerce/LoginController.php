<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function loginForm(){
        return view('ecommerce.login');
    }

    public function login(Request $request){
        // validate request
        $this->validate($request,[
            'email' => 'required|exists:customers,email',
            'password' => 'required|string'
        ]);

        // take only email and password on request
        $auth = $request->only('email', 'password');
        $auth['status'] = 1; //the status must be 1

        // check auth with guard customer, attempt the process from $auth
        if (auth()->guard('customer')->attempt($auth)) {
            // return redirect dashoard
            return redirect()->intended(route('customer.dashboard'));
        }

        // failed
        return redirect()->back()->with(['error' => 'Wrong Email / Password']);
    }

    public function logout(){
        
        auth()->guard('customer')->logout();
        return redirect(route('customer.login'));
    }

    public function dashboard(){
        return view('ecommerce.dashboard');
    }
}
