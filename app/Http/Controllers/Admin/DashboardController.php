<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DasboardServices;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman Dashboard Index
     * 
     *@return \Illuminate\View\View
     */
    public function index()
    {
        $data = (new DasboardServices)->getDasboardData();

        return view('dashboard', compact('data'));
    }
}
