<?php

namespace App\Http\Controllers\Ecommerce\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{

    /**
     * Menampilkan Halaman Email Verifiy untuk Customer yang belum memverifikasi Email
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show()
    {
        if (!auth('customer')->user()->hasVerifiedEmail()) {
            return view('ecommerce.auth.verified');
        };
        return redirect(route('front'));
    }
    /**
     * Method ini mengatasi Request Email Verification
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function request()
    {
        auth('customer')->user()->sendEmailVerificationNotification();

        return back()->with(['succcess' => 'Verification Link sent']);
    }

    /**
     * Memverifikasi akun Customer 
     * 
     * @param EmailVerificationRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->to('/');
    }
}
