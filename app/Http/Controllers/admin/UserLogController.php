<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLogs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserLogController extends Controller
{
    public function all_user_logs(Request $request) {
        if ($request->ajax()) {
            $query = UserLogs::query();
            if (!empty($request->user_id)) {
                $query->where('user_id', '=', $request->user_id);
            }
            if (!empty($request->current_date)) {
                $query->whereDate('created_at', '=', $request->current_date);
            }
            if (!empty($request->start_date)) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }

            if (!empty($request->end_date)) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
            $data = $query->orderBy('id', 'DESC')->get();
            return Datatables::of($data)
                ->addColumn('Activity', function($data) {
                    $activity = '<div class="text-capitalize" style="overflow: auto; width: 100%;">' .$data->activity.'</div>';
                    return $activity;
                })
                ->addColumn('ProfileView', function ($data) {
                    $userdata = User::where('id', $data->user_id)->first();
                    return '<div class="d-flex align-items-center">
                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3"></div>
                        <div class="d-flex flex-column">
                            <a href="#" class="text-gray-800 text-hover-primary mb-1">' . ucfirst($userdata->name) . '</a>
                            <span>' . $userdata->email . '</span>
                        </div>
                    </div>';
                })
                ->addColumn('Date', function ($data) {
                    return Carbon::parse($data->created_at)->diffForHumans();
                })
                ->rawColumns(['ProfileView','Date','Activity'])
                ->make(true);
        }
        $users = User::where('status','active')->orderBy('id', 'DESC')->get();
        return view('admin.pages.user_log.all', compact('users'));
    }
}
