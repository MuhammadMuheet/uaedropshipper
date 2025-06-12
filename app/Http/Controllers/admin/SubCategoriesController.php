<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SubCategoriesController extends Controller
{
    public function all_sub_categories(Request $request) {
        if (ActivityLogger::hasPermission('sub_categories', 'view')) {
            if ($request->ajax()) {
                $data = SubCategory::orderBy('id', 'DESC')->get();
                return Datatables::of($data)
                    ->addColumn('Category', function($data) {
                        $CategoryData = Category::where('id', $data->category_id)->first();
                        if (!empty($CategoryData)){
                            return ucfirst($CategoryData->category);
                        }else{
                            return "N/A";
                        }
                    })
                    ->addColumn('SubCategory', function($data) {
                        return ucfirst($data->sub_category);
                    })
                    ->addColumn('statusView', function($data) {
                        return $data->status == 'active'
                            ? "<div class='badge bg-success'>Active</div>"
                            : "<div class='badge bg-danger'>Block</div>";
                    })
                    ->addColumn('action', function($data) {
                        if (ActivityLogger::hasPermission('sub_categories', 'status')) {
                            $action = $data->status == 'block'
                                ? '<a onclick="changeStatus(1, ' . $data->id . ')" class="btn btn-success btn-sm me-2"><i style="font-size: 16px; padding: 0;" class="fa-solid fa-unlock"></i></i></a>'
                                : '<a onclick="changeStatus(0, ' . $data->id . ')" class="btn btn-danger btn-sm me-2"><i style="font-size: 16px; padding: 0;" class="fa-solid fa-lock"></i></a>';
                        }else{
                            $action ='';
                        }
                        if (ActivityLogger::hasPermission('sub_categories', 'edit')) {
                            $action .=
                                '<a href="#" class=" edit btn btn-sm btn-info" data-id="'.$data->id.'" data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                        <i style="font-size: 16px; padding: 0;" class="fa-solid fa-pen-to-square"></i>
                    </a>';
                        }
                        if (ActivityLogger::hasPermission('sub_categories', 'delete')) {
                            $action .='
                    <a onclick="deleteItem(' . $data->id . ')" class="btn btn-danger btn-sm" style="margin-right: 5px;">
                       <i style="font-size: 16px; padding: 0;" class="fa-regular fa-trash-can"></i>
                    </a>';
                        }
                        return $action;
                    })
                    ->rawColumns(['Category','SubCategory','statusView', 'action'])
                    ->make(true);
            }
            ActivityLogger::UserLog('Open User Product Sub Categories Page');
            $Categories = Category::where('status', 'active')->get();
            return view('admin.pages.products.sub_categories',compact('Categories'));
        }
    }
    public function add_sub_categories(Request $request)
    {
        if (ActivityLogger::hasPermission('sub_categories', 'add')) {
            if (empty($request->sub_category)) {
                return response()->json(2);
            }if (empty($request->category_id)) {
                return response()->json(2);
            }
            $category = Category::find($request->category_id);
            if (!$category) {
                return response()->json( 3);
            }
            try {
                SubCategory::create([
                    'category_id'  => $request->category_id,
                    'sub_category' => $request->sub_category,
                ]);
                ActivityLogger::UserLog('Add Sub Category '.$request->sub_category);
                return response()->json(1);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }
    public function delete_sub_categories(Request $request)
    {
        if (ActivityLogger::hasPermission('sub_categories', 'delete')) {
            $id=$request->id;
            $detail = SubCategory::find($id);
            SubCategory::destroy($detail->id);
            ActivityLogger::UserLog('Delete Sub Category '.$detail->sub_category);
            echo 1;
            exit();
        }
    }
    public function status_sub_categories(Request $request)
    {
        if (ActivityLogger::hasPermission('sub_categories', 'status')) {
            $id=$request->id;
            $status=$request->status;
            $detail = SubCategory::find($id);
            if ($detail) {
                if ($status == '1') {
                    $updated = $detail->update(['status' => 'active']);
                    ActivityLogger::UserLog('Update Sub Category '.$detail->sub_category.' status to active');
                    echo 2;
                    exit();
                } else {
                    $updated = $detail->update(['status' => 'block']);
                    ActivityLogger::UserLog('Update SubCategory '.$detail->sub_category.' status to block');
                    echo 1;
                    exit();
                }
            }
        }
    }
    public function get_sub_categories(Request $request){
        if (ActivityLogger::hasPermission('sub_categories', 'edit')) {
            $id=$request->id;
            $Data=SubCategory::find($id);
            return response()->json($Data);
        }
    }
    public function update_sub_categories(Request $request)
    {
        if (ActivityLogger::hasPermission('sub_categories', 'edit')) {
            if (empty($request->id)) {
                return response()->json(2);
            }
            if (empty($request->edit_sub_category)) {
                return response()->json(2);
            }
            try {
                $data = SubCategory::find($request->id);
                if ($data) {
                    $updateData = [
                        'sub_category' => $request->edit_sub_category,
                    ];
                    $data->update($updateData);
                    ActivityLogger::UserLog('Update Sub Category '.$request->edit_Sub_category);
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
