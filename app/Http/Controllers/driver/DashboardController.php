<?php

namespace App\Http\Controllers\driver;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $appointments = collect([
            (object)[
                'date' => Carbon::now()->addDays(1)->setTime(10, 30),
                'title' => 'Meeting with Client A',
                'jitsi_link' => 'meeting-client-a'
            ],
            (object)[
                'date' => Carbon::now()->addDays(2)->setTime(14, 00),
                'title' => 'Team Standup',
                'jitsi_link' => 'team-standup'
            ],
            (object)[
                'date' => Carbon::now()->addDays(2)->setTime(16, 00),
                'title' => 'Project Discussion',
                'jitsi_link' => 'project-discussion'
            ]
        ]);

        // Dummy data for users
        $users = collect([
            (object)['company_name' => 'TechCorp']
        ]);
       $all_orders = Order::where('driver_id',Auth::user()->id)->orderBy('id', 'DESC')->count();
       $shipped_orders = Order::where('driver_id',Auth::user()->id)->where('status','Shipped')->orderBy('id', 'DESC')->count();
       $delivered_orders = Order::where('driver_id',Auth::user()->id)->where('status','Delivered')->orderBy('id', 'DESC')->count();
       $canceled_orders = Order::where('driver_id',Auth::user()->id)->where('status','Cancelled')->orderBy('id', 'DESC')->count();
        ActivityLogger::UserLog('Logged in Driver '.Auth::user()->name);
        return view('driver.dashboard', compact('all_orders','shipped_orders','delivered_orders','canceled_orders','appointments', 'users'));
    }
}
