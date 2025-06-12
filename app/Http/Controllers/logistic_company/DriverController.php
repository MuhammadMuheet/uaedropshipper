<?php

namespace App\Http\Controllers\logistic_company;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class DriverController extends Controller
{
    public function index(Request $request) {
            if ($request->ajax()) {
                    $data = User::where('role','=', 'driver')->where('company_id','=', Auth::user()->id)->orderBy('id', 'DESC')->get();
                return Datatables::of($data)
                    ->addColumn('statusView', function($data) {
                        return $data->status == 'active'
                            ? "<div class='badge bg-success'>Active</div>"
                            : "<div class='badge bg-danger'>Block</div>";
                    })
                    ->addColumn('action', function($data) {
                            $action = $data->status == 'block'
                                ? '<a onclick="changeStatus(1, ' . $data->id . ')" class="btn btn-success btn-sm me-2"><i style="font-size: 16px; padding: 0;" class="fa-solid fa-unlock"></i></i></a>'
                                : '<a onclick="changeStatus(0, ' . $data->id . ')" class="btn btn-danger btn-sm me-2"><i style="font-size: 16px; padding: 0;" class="fa-solid fa-lock"></i></a>';
                            $action .='<a href="#" class=" edit btn btn-sm btn-info" data-id="'.$data->id.'" data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                        <i style="font-size: 16px; padding: 0;" class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <a onclick="deleteItem(' . $data->id . ')" class="btn btn-danger btn-sm" style="margin-right: 5px;">
                       <i style="font-size: 16px; padding: 0;" class="fa-regular fa-trash-can"></i>
                    </a>';
                        return $action;
                    })
                    ->rawColumns(['statusView', 'action'])
                    ->make(true);
            }
            return view('logistic_company.pages.drivers.index');
    }
    public function add_drivers(Request $request)
    {
            try {
                $user = User::create([
                    'company_id' => Auth::user()->id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'password' => Hash::make($request->password),
                    'role' => 'driver',
                    'status' => 'active',
                ]);
                return response()->json(1);
            } catch (\Exception $e) {
                return response()->json(['error' => $e], 500);
            }
    }
    public function delete_drivers(Request $request)
    {
            $id=$request->id;
            $detail = User::find($id);
            User::destroy($detail->id);
            echo 1;
            exit();
    }
    public function status_drivers(Request $request)
    {

            $id=$request->id;
            $status=$request->status;
            $detail = User::find($id);
            if ($detail) {
                if ($status == '1') {
                    $updated = $detail->update(['status' => 'active']);
                    ActivityLogger::UserLog('Update User '.$detail->name.' Status to Active');
                    echo 2;
                    exit();
                } else {
                    $updated = $detail->update(['status' => 'block']);
                    ActivityLogger::UserLog('Update User '.$detail->name.' Status to Block');
                    echo 1;
                    exit();
                }
        }
    }
    public function get_drivers(Request $request){
            $id=$request->id;
            $Data=User::find($id);
            return response()->json($Data);
    }
    public function update_drivers(Request $request)
    {
            try {
                $data = User::find($request->id);
                if ($data) {
                    $updateData = [
                        'name' => $request->edit_name,
                        'email' => $request->edit_email,
                        'mobile' => $request->edit_mobile,
                        'password' => Hash::make($request->edit_password),
                    ];
                    $data->update($updateData);
                    ActivityLogger::UserLog('Update User '.$request->edit_name);
                    return response()->json(1);
                } else {
                    return response()->json(3);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Something went wrong'], 500);
            }
    }
}
