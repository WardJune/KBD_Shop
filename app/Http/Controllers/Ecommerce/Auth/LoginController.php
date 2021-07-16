<?php

namespace App\Http\Controllers\Ecommerce\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Menampilkan Halaman Login
     * 
     * @return \Illuminate\View\View 
     */
    public function show()
    {
        session(['link' => url()->previous()]);
        return view('ecommerce.auth.login');
    }

    /**
     * Menangani Login Attempt Customer
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
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
            return redirect(session('link'));
        }

        return redirect()->back()->with(['error' => 'These credentials do not match our records. ']);
    }
}
