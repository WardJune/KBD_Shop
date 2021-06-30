<?php

namespace App\Http\Controllers\Ecommerce\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{

    public function show()
    {
        if (!auth('customer')->user()->hasVerifiedEmail()) {
            return view('ecommerce.auth.verified');
        };
        return redirect(route('front'));
    }
    public function request()
    {
        auth('customer')->user()->sendEmailVerificationNotification();

        return back()->with(['succcess' => 'Verification Link sent']);
    }

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->to('/');
    }
}
