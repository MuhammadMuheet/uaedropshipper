<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use App\Traits\imageUploadTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RoleController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::orderBy('id', 'DESC')->get();
            return Datatables::of($data)
                    ->addColumn('action',function ($data) {
                        if (ActivityLogger::hasPermission('user_role', 'permissions')) {
                            $action =
                                '<a href="'.route('permission_update', \Illuminate\Support\Facades\Crypt::encrypt($data->id)).'" class="btn btn-sm btn-warning me-2" >
                       Edit Permissions
                    </a>  ';
                        }else{
                            $action = '';
                        }
                        if (ActivityLogger::hasPermission('user_role', 'edit')) {
                            $action .=
                                ' <a href="#" class=" edit btn btn-sm btn-info me-2" data-id="' . $data->id . '" data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                        <i style="font-size: 16px; padding: 0;" class="fa-solid fa-pen-to-square"></i>
                    </a>';
                        }
                        if (ActivityLogger::hasPermission('user_role', 'delete')) {
                            $action .=
                                ' <a onclick="deleteItem(' . $data->id . ')" class="btn btn-danger btn-sm" style="margin-right: 5px;">
                       <i style="font-size: 16px; padding: 0;" class="fa-regular fa-trash-can"></i>
                    </a>';
                        }
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        ActivityLogger::UserLog('Open Role page');
        return view('admin.pages.role.index');
    }
    public function add_role(Request $request)
    {
        if (ActivityLogger::hasPermission('user_role', 'add')) {
            if (empty($request->name)) {
                return response()->json(2);
            }
            try {
                Role::create([
                    'name' => $request->name,
                ]);
                ActivityLogger::UserLog('add Role '.$request->name);
                return response()->json(1);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }
    public function delete_role(Request $request)
    {
        if (ActivityLogger::hasPermission('user_role', 'delete')) {
        $id = $request->id;
        $role = Role::find($id);
        if (!$role) {
            echo 0;
            exit();
        }
        $userCount = User::where('type', $id)->count();
        if ($userCount > 0) {
            echo 2;
            exit();
        }
        $role->delete();
            ActivityLogger::UserLog('delete Role '.$role->name);
            echo 1;
        exit();
    }
    }

    public function get_role(Request $request)
    {
        if (ActivityLogger::hasPermission('user_role', 'edit')) {
            $id = $request->id;
            $Data = Role::find($id);

            return response()->json($Data);
        }
    }
    public function update_role(Request $request)
    {
        if (ActivityLogger::hasPermission('user_role', 'edit')) {
        if (empty($request->id)) {
            return response()->json(2);
        }
        if (empty($request->edit_name)) {
            return response()->json(2);
        }
        try {
            $data = Role::find($request->id);
            if ($data) {
                $updateData = [
                    'name' => $request->edit_name,
                ];
                $data->update($updateData);
                ActivityLogger::UserLog('Update Role '.$request->edit_name);
                return response()->json(1);
            } else {
                return response()->json(3);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
    }
    public function permission_update(Request $request, $role_id)
    {
        if (ActivityLogger::hasPermission('user_role', 'permissions')) {
        $permission = Permission::where('role_id',decrypt($role_id))->first();
        $role = Role::where('id',decrypt($role_id))->first();
        ActivityLogger::UserLog('open update permissions page');
        return view('admin.pages.role.edit', compact('permission', 'role'));
    }
    }
    public function update_permission(Request $request)
    {
        if (ActivityLogger::hasPermission('user_role', 'permissions')) {
        $permission = Permission::updateOrCreate(
            ['role_id' => $request->input('role_id')],
            [
                'users' => implode(',', $request->users ?? []),
                'user_role' => implode(',', $request->user_role ?? []),
                'user_logs' => $request->user_log ?? '',
                'payments' => $request->payments ?? '',
                'settings' => implode(',', $request->setting ?? []),
                'sellers' => implode(',', $request->sellers ?? []),
                'logistic_companies' => implode(',', $request->logistic_companies ?? []),
                'categories' => implode(',', $request->categories ?? []),
                'sub_categories' => implode(',', $request->sub_categories ?? []),
                'products' => implode(',', $request->products ?? []),
                'orders' => implode(',', $request->orders ?? []),
                'locations' => implode(',', $request->locations ?? []),
            ]
        );
        $role = Role::where('id',$permission->role_id)->first();
        ActivityLogger::UserLog('Update permissions with role '.$role->name);
        return response()->json(1);
    }
    }
}
