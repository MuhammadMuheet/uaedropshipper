<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{
    public function index(Request $request) {
        if (ActivityLogger::hasPermission('users', 'view')) {
        if ($request->ajax()) {
            $data = User::where('role','=', 'sub_admin')->orderBy('id', 'DESC')->get();
            return Datatables::of($data)

                ->addColumn('statusView', function($data) {
                    return $data->status == 'active'
                        ? "<div class='badge bg-success'>Active</div>"
                        : "<div class='badge bg-danger'>Block</div>";
                })
                ->addColumn('Type', function($data) {
                    $userRole = Role::where('id','=', $data->type)->first();
                    if (!empty($userRole->name)){
                        return  $userRole->name;
                    }else{
                        return  'N/A';
                    }
                })
                ->addColumn('action', function($data) {
                    if (ActivityLogger::hasPermission('users', 'status')) {
                        $action = $data->status == 'block'
                            ? '<a onclick="changeStatus(1, ' . $data->id . ')" class="btn btn-success btn-sm me-2"><i style="font-size: 16px; padding: 0;" class="fa-solid fa-unlock"></i></i></a>'
                            : '<a onclick="changeStatus(0, ' . $data->id . ')" class="btn btn-danger btn-sm me-2"><i style="font-size: 16px; padding: 0;" class="fa-solid fa-lock"></i></a>';
                    }else{
                        $action ='';
                    }
                    if (ActivityLogger::hasPermission('users', 'edit')) {
                        $action .='<a href="#" class=" edit btn btn-sm btn-info" data-id="'.$data->id.'" data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                        <i style="font-size: 16px; padding: 0;" class="fa-solid fa-pen-to-square"></i>
                    </a>';
                    }
                    if (ActivityLogger::hasPermission('users', 'delete')) {
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
        $roles = Role::all();
            ActivityLogger::UserLog('open users page');
        return view('admin.pages.all-users.index', compact('roles'));
    }
    }
    public function add_users(Request $request)
    {
        if (ActivityLogger::hasPermission('users', 'add')) {
            if (empty($request->name) || empty($request->email)|| empty($request->mobile)|| empty($request->password)|| empty($request->role)){
                return response()->json(2);
            }
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'show_password' => $request->password,
                'type' => $request->role,
                'role' => 'sub_admin',
                'status' => 'active',
            ]);
            ActivityLogger::UserLog('add users '.$request->name);
            return response()->json(1);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }
    }
    public function delete_user(Request $request)
    {
        if (ActivityLogger::hasPermission('users', 'delete')) {
        $id=$request->id;
        $detail = User::find($id);
        User::destroy($detail->id);
            ActivityLogger::UserLog('delete users '.$detail->name);
            echo 1;
        exit();
    }
    }
    public function status_user(Request $request)
    {
        if (ActivityLogger::hasPermission('users', 'status')) {
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
    public function get_user(Request $request){
        if (ActivityLogger::hasPermission('users', 'edit')) {
        $id=$request->id;
        $Data=User::find($id);
        return response()->json($Data);
    }
    }
    public function update_user(Request $request)
    {
        if (ActivityLogger::hasPermission('users', 'edit')) {
        try {
            $data = User::find($request->id);
            if (empty($request->edit_name) || empty($request->edit_email)|| empty($request->edit_mobile)|| empty($request->edit_role)){
                return response()->json(2);
            }
            if ($data) {
                $updateData = [
                    'name' => $request->edit_name,
                    'email' => $request->edit_email,
                    'mobile' => $request->edit_mobile,
                    'type' => $request->edit_role,
                ];
                $data->update($updateData);
                if (!empty($request->edit_password)){
                    $uppercase = preg_match('@[A-Z]@', $request->edit_password);
                    $lowercase = preg_match('@[a-z]@', $request->edit_password);
                    $number = preg_match('@[0-9]@', $request->edit_password);

                    if (!$uppercase || !$lowercase || !$number || strlen($request->edit_password) < 8) {
                        return response()->json(4);
                    }
                    $data->password = Hash::make($request->edit_password);
                    $data->show_password = $request->edit_password;
                    $data->save();
                }
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
