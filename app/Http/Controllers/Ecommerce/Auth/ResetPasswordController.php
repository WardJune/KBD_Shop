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
    /**
     * Menampilkan Halaman Forgot Pasword
     * 
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('ecommerce.auth.forgot-password');
    }

    /**
     * Method ini mengatasi Request Reset Password 
     * 
     * 
     * @var mixed $user
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function request()
    {
        $this->validate(request(), ['email' => 'required|email|exists:customers,email']);
        $user = Customer::whereEmail(request()->email)->first();

        if ($user) {
            $token = Password::getRepository()->create($user);
            $user->sendPasswordResetNotification($token);

            return back()->with(['message' => 'We have email your password reset link, please check your email']);
        }
        return back()->withErrors(['email' => 'These credentials do not match our records.']);
    }

    /**
     * Menampilkan Halaman Form Reset Password 
     * 
     * @param mixed $token
     * 
     * @return \Illuminate\View\View
     */
    public function formReset($token)
    {
        return view('ecommerce.auth.forgot-password-form', compact('token'));
    }

    /**
     * Method ini mengatasi Update Password Customer
     * 
     * @var \Illuminate\Database\Query\Builder $email  Query data dari password_resets
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
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
