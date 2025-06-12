<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\imageUploadTrait;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Contract;
use App\Models\Renter;
use App\Models\Reservation;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;


class DashboardController extends Controller
{



    public function index()
    {
        ActivityLogger::UserLog('Logged in User '.Auth::user()->name);
        return view('admin.dashboard');
    }




}
