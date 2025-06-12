<?php

namespace App\Http\Controllers\logistic_company;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        ActivityLogger::UserLog('Logged in Logistic Company '.Auth::user()->name);
        return view('logistic_company.dashboard');
    }
}
