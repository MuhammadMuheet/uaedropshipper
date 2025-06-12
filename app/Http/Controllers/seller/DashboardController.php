<?php

namespace App\Http\Controllers\seller;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        ActivityLogger::UserLog('Logged in Seller '.Auth::user()->name);
        return view('seller.dashboard');
    }
}
