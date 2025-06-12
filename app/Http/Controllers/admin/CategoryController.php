<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    public function all_categories(Request $request) {
        if (ActivityLogger::hasPermission('categories', 'view')) {
            if ($request->ajax()) {
                $data = Category::orderBy('id', 'DESC')->get();
                return Datatables::of($data)
                    ->addColumn('Category', function($data) {
                        return ucfirst($data->category);
                    })
                    ->addColumn('statusView', function($data) {
                        return $data->status == 'active'
                            ? "<div class='badge bg-success'>Active</div>"
                            : "<div class='badge bg-danger'>Block</div>";
                    })
                    ->addColumn('action', function($data) {
                        if (ActivityLogger::hasPermission('categories', 'status')) {
                        $action = $data->status == 'block'
                            ? '<a onclick="changeStatus(1, ' . $data->id . ')" class="btn btn-success btn-sm me-2"><i style="font-size: 16px; padding: 0;" class="fa-solid fa-unlock"></i></i></a>'
                            : '<a onclick="changeStatus(0, ' . $data->id . ')" class="btn btn-danger btn-sm me-2"><i style="font-size: 16px; padding: 0;" class="fa-solid fa-lock"></i></a>';
                        }else{
                            $action ='';
                        }
                        if (ActivityLogger::hasPermission('categories', 'edit')) {
                        $action .=
                            '<a href="#" class=" edit btn btn-sm btn-info" data-id="'.$data->id.'" data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                        <i style="font-size: 16px; padding: 0;" class="fa-solid fa-pen-to-square"></i>
                    </a>';
                        }
                        if (ActivityLogger::hasPermission('categories', 'delete')) {
                        $action .='
                    <a onclick="deleteItem(' . $data->id . ')" class="btn btn-danger btn-sm" style="margin-right: 5px;">
                       <i style="font-size: 16px; padding: 0;" class="fa-regular fa-trash-can"></i>
                    </a>';
                        }
                        return $action;
                    })
                    ->rawColumns(['Category','statusView', 'action'])
                    ->make(true);
            }
            ActivityLogger::UserLog('Open User Product Categories Page');

            return view('admin.pages.products.categories');
        }
    }
    public function add_categories(Request $request)
    {
        if (ActivityLogger::hasPermission('categories', 'add')) {
            if (empty($request->category)) {
                return response()->json(2);
            }
            try {
                Category::create([
                    'category' => $request->category,
                ]);
                ActivityLogger::UserLog('Add Category '.$request->category);
                return response()->json(1);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }
    public function delete_categories(Request $request)
    {
        if (ActivityLogger::hasPermission('categories', 'delete')) {
            $id=$request->id;
            $detail = Category::find($id);
            if (!$detail) {
                echo 0;
                exit();
            }
            if ($detail->subcategories()->exists()) {
                echo 2;
                exit();
            }
            Category::destroy($detail->id);
            ActivityLogger::UserLog('Delete Category '.$detail->category);
            echo 1;
            exit();
        }else{
            echo 0;
            exit();
        }
    }
    public function status_categories(Request $request)
    {
        if (ActivityLogger::hasPermission('categories', 'status')) {
            $id=$request->id;
            $status=$request->status;
            $detail = Category::find($id);
            if ($detail) {
                if ($status == '1') {
                    $updated = $detail->update(['status' => 'active']);
                    ActivityLogger::UserLog('Update Category '.$detail->category.' status to active');
                    echo 2;
                    exit();
                } else {
                    $updated = $detail->update(['status' => 'block']);
                    ActivityLogger::UserLog('Update Category '.$detail->category.' status to block');
                    echo 1;
                    exit();
                }
            }
        }
    }
    public function get_categories(Request $request){
        if (ActivityLogger::hasPermission('categories', 'edit')) {
            $id=$request->id;
            $Data=Category::find($id);
            return response()->json($Data);
        }
    }
    public function update_categories(Request $request)
    {
        if (ActivityLogger::hasPermission('categories', 'edit')) {
            if (empty($request->id)) {
                return response()->json(2);
            }
            if (empty($request->edit_category)) {
                return response()->json(2);
            }
            try {
                $data = Category::find($request->id);
                if ($data) {
                    $updateData = [
                        'category' => $request->edit_category,
                    ];
                    $data->update($updateData);
                    ActivityLogger::UserLog('Update Category '.$request->edit_category);
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
