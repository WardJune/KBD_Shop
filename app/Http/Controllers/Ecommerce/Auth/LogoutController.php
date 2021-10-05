<?php

namespace App\Http\Controllers\Ecommerce\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function handle()
    {
        auth('customer')->logout();

        return redirect()->to(RouteServiceProvider::HOME);
    }
}
