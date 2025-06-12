<?php

namespace App\Http\Controllers\seller;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\SellerPermission;
use App\Models\SellerRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SellerRoleController extends Controller
{

    public function index(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('seller_role', 'view')) {
        if ($request->ajax()) {
            if (Auth::user()->role == 'seller'){
                $data = SellerRole::where('seller_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
            }else{
                $data = SellerRole::where('seller_id', Auth::user()->seller_id)->orderBy('id', 'DESC')->get();
            }
            return Datatables::of($data)
                ->addColumn('action',function ($data) {
                    if (ActivityLogger::hasSellerPermission('seller_role', 'permissions')) {
                        $action =
                            '<a href="'.route('seller_permission_update', \Illuminate\Support\Facades\Crypt::encrypt($data->id)).'" class="btn btn-sm btn-warning me-2" >
                       Edit Permissions
                    </a>  ';
                    }else{
                        $action = '';
                    }
                    if (ActivityLogger::hasSellerPermission('seller_role', 'edit')) {
                        $action .=
                            ' <a href="#" class=" edit btn btn-sm btn-info me-2" data-id="' . $data->id . '" data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                        <i style="font-size: 16px; padding: 0;" class="fa-solid fa-pen-to-square"></i>
                    </a>';
                    }
                    if (ActivityLogger::hasSellerPermission('seller_role', 'delete')) {
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
            ActivityLogger::UserLog('Open role Page');
        return view('seller.pages.role.index');
    }
    }
    public function add_role(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('seller_role', 'add')) {
            if (empty($request->name)) {
                return response()->json(2);
            }
            if (Auth::user()->role == 'seller'){
                $seller_id = Auth::user()->id;
            }else{
                $seller_id = Auth::user()->seller_id;
            }
            try {
                SellerRole::create([
                    'seller_id' => $seller_id,
                    'name' => $request->name,
                ]);
                ActivityLogger::UserLog('Add role '.$request->name);
                return response()->json(1);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }
    public function delete_role(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('seller_role', 'delete')) {
            $id = $request->id;
            $role = SellerRole::find($id);
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
            ActivityLogger::UserLog('Delete role '.$role->name);
            echo 1;
            exit();
        }
    }

    public function get_role(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('seller_role', 'edit')) {
            $id = $request->id;
            $Data = SellerRole::find($id);
            return response()->json($Data);
        }
    }
    public function update_role(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('seller_role', 'edit')) {
            if (empty($request->id)) {
                return response()->json(2);
            }
            if (empty($request->edit_name)) {
                return response()->json(2);
            }
            try {
                $data = SellerRole::find($request->id);
                if ($data) {
                    $updateData = [
                        'name' => $request->edit_name,
                    ];
                    $data->update($updateData);
                    ActivityLogger::UserLog('Update role '.$request->edit_name);
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
        if (ActivityLogger::hasSellerPermission('seller_role', 'permissions')) {
            $permission = SellerPermission::where('role_id',decrypt($role_id))->first();
            $role = SellerRole::where('id',decrypt($role_id))->first();
            ActivityLogger::UserLog('open update permissions page');
            return view('seller.pages.role.edit', compact('permission', 'role'));
        }
    }
    public function update_permission(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('seller_role', 'permissions')) {
            $permission = SellerPermission::updateOrCreate(
                ['role_id' => $request->input('role_id')],
                [
                    'sub_sellers' => implode(',', $request->sub_sellers ?? []),
                    'seller_role' => implode(',', $request->seller_role ?? []),
                    'cart' => implode(',', $request->cart ?? []),
                    'orders' => implode(',', $request->orders ?? []),
                    'products' => $request->products ?? '',
                    'settings' => $request->settings ?? '',
                ]
            );
            $role = SellerRole::where('id',$permission->role_id)->first();
            ActivityLogger::UserLog('Update permissions with role '.$role->name);
            return response()->json(1);
        }
    }
}
