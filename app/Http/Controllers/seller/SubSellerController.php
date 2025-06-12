<?php

namespace App\Http\Controllers\seller;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\SellerRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class SubSellerController extends Controller
{
    public function index(Request $request) {
        if (ActivityLogger::hasSellerPermission('sub_sellers', 'view')) {
            if ($request->ajax()) {
                if(Auth::user()->role == 'seller'){
                    $data = User::where('role','=', 'sub_seller')->where('seller_id','=', Auth::user()->id)->orderBy('id', 'DESC')->get();
                }else{
                    $data = User::where('role','=', 'sub_seller')->where('seller_id','=', Auth::user()->seller_id)->orderBy('id', 'DESC')->get();
                }

                return Datatables::of($data)

                    ->addColumn('statusView', function($data) {
                        return $data->status == 'active'
                            ? "<div class='badge bg-success'>Active</div>"
                            : "<div class='badge bg-danger'>Block</div>";
                    })
                    ->addColumn('Type', function($data) {
                        $userRole = SellerRole::where('id','=', $data->type)->first();
                        if (!empty($userRole->name)){
                            return  $userRole->name;
                        }else{
                            return  'N/A';
                        }
                    })
                    ->addColumn('action', function($data) {
                        if (ActivityLogger::hasSellerPermission('sub_sellers', 'status')) {
                            $action = $data->status == 'block'
                                ? '<a onclick="changeStatus(1, ' . $data->id . ')" class="btn btn-success btn-sm me-2"><i style="font-size: 16px; padding: 0;" class="fa-solid fa-unlock"></i></i></a>'
                                : '<a onclick="changeStatus(0, ' . $data->id . ')" class="btn btn-danger btn-sm me-2"><i style="font-size: 16px; padding: 0;" class="fa-solid fa-lock"></i></a>';
                        }else{
                            $action ='';
                        }
                        if (ActivityLogger::hasSellerPermission('sub_sellers', 'edit')) {
                            $action .='<a href="#" class=" edit btn btn-sm btn-info" data-id="'.$data->id.'" data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                        <i style="font-size: 16px; padding: 0;" class="fa-solid fa-pen-to-square"></i>
                    </a>';
                        }
                        if (ActivityLogger::hasSellerPermission('sub_sellers', 'delete')) {
                            $action .='
                    <a onclick="deleteItem(' . $data->id . ')" class="btn btn-danger btn-sm" style="margin-right: 5px;">
                       <i style="font-size: 16px; padding: 0;" class="fa-regular fa-trash-can"></i>
                    </a>';
                        }
                        return $action;
                    })
                    ->rawColumns(['Type','statusView', 'action'])
                    ->make(true);
            }
            $roles = SellerRole::where('seller_id',Auth::user()->id)->get();

            return view('seller.pages.sub_seller.index', compact('roles'));
        }
    }
    public function add_sub_sellers(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('sub_sellers', 'add')) {
            if(Auth::user()->role == 'seller'){
                $seller_id = Auth::user()->id;
            }else{
                $seller_id = Auth::user()->seller_id;
            }
            try {
           $user = User::create([
                    'seller_id' => $seller_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'password' => Hash::make($request->password),
                    'type' => $request->role,
                    'role' => 'sub_seller',
                    'status' => 'active',
                ]);
                return response()->json(1);
            } catch (\Exception $e) {
                return response()->json(['error' => $e], 500);
            }
        }
    }
    public function delete_sub_seller(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('sub_sellers', 'delete')) {
            $id=$request->id;
            $detail = User::find($id);
            User::destroy($detail->id);
            echo 1;
            exit();
        }
    }
    public function status_sub_seller(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('sub_sellers', 'status')) {
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
    }
    public function get_sub_seller(Request $request){
        if (ActivityLogger::hasSellerPermission('sub_sellers', 'edit')) {
            $id=$request->id;
            $Data=User::find($id);
            return response()->json($Data);
        }
    }
    public function update_sub_seller(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('sub_sellers', 'edit')) {
            try {
                $data = User::find($request->id);
                if ($data) {
                    $updateData = [
                        'name' => $request->edit_name,
                        'email' => $request->edit_email,
                        'mobile' => $request->edit_mobile,
                        'password' => Hash::make($request->edit_password),
                        'type' => $request->edit_role,
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
}
