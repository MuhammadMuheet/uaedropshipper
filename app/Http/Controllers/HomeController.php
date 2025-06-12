<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->role == 'admin' || Auth::user()->role == 'sub_admin') {
                return redirect()->route('admin_dashboard');
            } else if (Auth::user()->role == 'seller' || Auth::user()->role == 'sub_seller') {
                return redirect()->route('seller_dashboard');
            }  else if (Auth::user()->role == 'logistic_company') {
                return redirect()->route('logistic_company_dashboard');
            } else if (Auth::user()->role == 'driver') {
                return redirect()->route('driver_dashboard');
            } else {
                return redirect()->route('login');
            }
        } else {
            return redirect()->route('login');
        }
    }
}
