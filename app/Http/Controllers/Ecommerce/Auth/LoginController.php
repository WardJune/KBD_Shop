<?php

namespace App\Http\Controllers\Ecommerce\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function show()
    {
        return view('ecommerce.auth.login');
    }

    public function handle(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:customers,email',
            'password' => 'required'
        ]);

        $user = auth('customer')->attempt(
            [
                'email' => $request->email,
                'password' => $request->password
            ],
            $request->has('remember')
        );

        if ($user) {
            return redirect()->to(RouteServiceProvider::HOME);
        }

        return redirect()->back()->with(['error' => 'These credentials do not match our records. ']);
    }
}
