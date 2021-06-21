<?php

namespace App\Http\Controllers\Ecommerce\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function show()
    {
        return view('ecommerce.auth.forgot-password');
    }

    public function request()
    {
        $this->validate(request(), ['email' => 'required|email|exists:customers,email']);

        $user = Customer::where('email', request()->email)->first();

        if ($user) {

            $token = Password::getRepository()->create($user);
            $user->sendPasswordResetNotification($token);

            return back()->with(['message' => 'We have email your password reset link, please check your email']);
        }

        return back()->withErrors(['email' => 'These credentials do not match our records.']);
    }

    public function formReset($token)
    {
        return view('ecommerce.auth.forgot-password-form', compact('token'));
    }

    public function update()
    {
        $this->validate(request(), [
            'token' => 'required',
            'email' => 'required|email|exists:customers,email',
            'password' => 'required|string|confirmed|min:8'
        ]);

        $email = DB::table('password_resets')->where('email', request()->email);

        if (!$email->first()) {
            return back()->withErrors(['email' => 'Invalid Token']);
        } else {
            $token = Hash::check(request()->token, $email->first()->token);
            if (!$token) {
                return back()->withErrors(['email' => 'Invalid Token']);
            }
        }

        Customer::where('email', request()->email)
            ->update(['password' => Hash::make(request()->password)]);

        $email->delete();

        return redirect(route('login'))->with(['status' => 'Your Password Has Been Changed']);
    }
}
