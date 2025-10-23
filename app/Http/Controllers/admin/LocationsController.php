<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\State;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AreasImport;
class LocationsController extends Controller
{
    public function all_states(Request $request) {
        if (ActivityLogger::hasPermission('locations', 'view')) {
            if ($request->ajax()) {
                $data = State::orderBy('id', 'DESC')->get();
                return Datatables::of($data)
                    ->addColumn('State', function($data) {
                        return ucfirst($data->state);
                    })
                    ->addColumn('action', function($data) {
                        $action ='';
                        if (ActivityLogger::hasPermission('locations', 'edit')) {
                            $action .=
                                '<a href="#" class=" edit btn btn-sm btn-info" data-id="'.$data->id.'" data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                        <i style="font-size: 16px; padding: 0;" class="fa-solid fa-pen-to-square"></i>
                    </a>';
                        }
                        if (ActivityLogger::hasPermission('locations', 'delete')) {
                            $action .='
                    <a onclick="deleteItem(' . $data->id . ')" class="btn btn-danger btn-sm" style="margin-right: 5px;">
                       <i style="font-size: 16px; padding: 0;" class="fa-regular fa-trash-can"></i>
                    </a>';
                        }
                        return $action;
                    })
                    ->rawColumns(['State', 'action'])
                    ->make(true);
            }
            ActivityLogger::UserLog('Open State Page');

            return view('admin.pages.locations.states');
        }
    }
    public function add_states(Request $request)
    {
        if (ActivityLogger::hasPermission('locations', 'add')) {
            if (empty($request->state)) {
                return response()->json(2);
            }
            try {
                State::create([
                    'state' => $request->state,
                ]);
                ActivityLogger::UserLog('Add State '.$request->state);
                return response()->json(1);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }
    public function delete_states(Request $request)
    {
        if (ActivityLogger::hasPermission('locations', 'delete')) {
            $id=$request->id;
            $detail = State::find($id);
            if (!$detail) {
                echo 0;
                exit();
            }
            if ($detail->areas()->exists()) {
                echo 2;
                exit();
            }
            State::destroy($detail->id);
            ActivityLogger::UserLog('Delete State '.$detail->state);
            echo 1;
            exit();
        }else{
            echo 0;
            exit();
        }
    }

    public function get_states(Request $request){
        if (ActivityLogger::hasPermission('locations', 'edit')) {
            $id=$request->id;
            $Data=State::find($id);
            return response()->json($Data);
        }
    }
    public function update_states(Request $request)
    {
        if (ActivityLogger::hasPermission('locations', 'edit')) {
            if (empty($request->id)) {
                return response()->json(2);
            }
            if (empty($request->edit_state)) {
                return response()->json(2);
            }
            try {
                $data = State::find($request->id);
                if ($data) {
                    $updateData = [
                        'state' => $request->edit_state,
                    ];
                    $data->update($updateData);
                    ActivityLogger::UserLog('Update State '.$request->edit_state);
                    return response()->json(1);
                } else {
                    return response()->json(3);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }
    public function all_areas(Request $request) {
        if (ActivityLogger::hasPermission('locations', 'view')) {
            if ($request->ajax()) {
                $data = Area::orderBy('id', 'DESC')->get();
                return Datatables::of($data)
                    ->addColumn('State', function($data) {
                        $CategoryData = State::where('id', $data->state_id)->first();
                        if (!empty($CategoryData)){
                            return ucfirst($CategoryData->state);
                        }else{
                            return "N/A";
                        }
                    })
                    ->addColumn('Area', function($data) {
                        return ucfirst($data->area);
                    })
                    ->addColumn('BulkAction', function($data) {
                        $BulkAction = '<div class="form-check form-check-sm form-check-custom form-check-solid"><input class="form-check-input" type="checkbox" name="bulk_action[]" value="'.$data->id.'" ></div>';
                        return $BulkAction;
                    })
                    ->addColumn('Shipping', function($data) {
                        if (!empty($data->shipping)){
                            return ucfirst($data->shipping);
                        }else{
                            return 'N/A';
                        }
                    })
                    ->addColumn('action', function($data) {
                            $action ='';
                        if (ActivityLogger::hasPermission('locations', 'edit')) {
                            $action .=
                                '<a href="#" class=" edit btn btn-sm btn-info" data-id="'.$data->id.'" data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                        <i style="font-size: 16px; padding: 0;" class="fa-solid fa-pen-to-square"></i>
                    </a>';
                        }
                        if (ActivityLogger::hasPermission('locations', 'delete')) {
                            $action .='
                    <a onclick="deleteItem(' . $data->id . ')" class="btn btn-danger btn-sm" style="margin-right: 5px;">
                       <i style="font-size: 16px; padding: 0;" class="fa-regular fa-trash-can"></i>
                    </a>';
                        }
                        return $action;
                    })
                    ->rawColumns(['Shipping','BulkAction','State','Area', 'action'])
                    ->make(true);
            }
            ActivityLogger::UserLog('Open Area Page');
            $states = State::all();
            return view('admin.pages.locations.areas',compact('states'));
        }
    }
    public function add_areas(Request $request)
    {
        if (ActivityLogger::hasPermission('locations', 'add')) {
            if (empty($request->area)) {
                return response()->json(2);
            }
            if (empty($request->state_id)) {
                return response()->json(2);
            }
            if (empty($request->shipping)) {
                return response()->json(2);
            }
            $state = State::find($request->state_id);
            if (!$state) {
                return response()->json( 3);
            }
            try {
                Area::create([
                    'state_id'  => $request->state_id,
                    'area' => $request->area,
                    'shipping' => $request->shipping,
                ]);
                ActivityLogger::UserLog('Add Area '.$request->area);
                return response()->json(1);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }
    public function delete_areas(Request $request)
    {
        if (ActivityLogger::hasPermission('locations', 'delete')) {
            $id=$request->id;
            $detail = Area::find($id);
            Area::destroy($detail->id);
            ActivityLogger::UserLog('Delete Area '.$detail->area);
            echo 1;
            exit();
        }
    }
    public function get_areas(Request $request){
        if (ActivityLogger::hasPermission('locations', 'edit')) {
            $id=$request->id;
            $Data=Area::find($id);
            return response()->json($Data);
        }
    }
    public function update_areas(Request $request)
    {
        if (ActivityLogger::hasPermission('locations', 'edit')) {
            if (empty($request->id)) {
                return response()->json(2);
            }
            if (empty($request->edit_area)) {
                return response()->json(2);
            }
            if (empty($request->edit_state_id)) {
                return response()->json(2);
            }
            if (empty($request->edit_shipping)) {
                return response()->json(2);
            }
            try {
                $data = Area::find($request->id);
                if ($data) {
                    $updateData = [
                        'state_id' => $request->edit_state_id,
                        'area' => $request->edit_area,
                        'shipping' => $request->edit_shipping,
                    ];
                    $data->update($updateData);
                    ActivityLogger::UserLog('Update Area '.$request->edit_area);
                    return response()->json(1);
                } else {
                    return response()->json(3);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }
    public function bulk_update_area(Request $request)
    {
        if (ActivityLogger::hasPermission('locations', 'edit')) {
            if (empty($request->selectedareas) || empty($request->bulk_shipping_fee)) {
                return response()->json(2);
            }
            $areasIds = json_decode($request->selectedareas, true);
            Area::whereIn('id', $areasIds)
                ->update([
                    'shipping' => $request->bulk_shipping_fee,
                ]);
            return response()->json(1);
        }
    }
    public function import(Request $request)
{
    if (!ActivityLogger::hasPermission('locations', 'add')) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized action.'
        ], 403);
    }

    $validator = Validator::make($request->all(), [
        'import_file' => 'required|file|mimes:xlsx,xls,csv'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid file format. Please upload an Excel file.'
        ]);
    }

    try {
        $import = new AreasImport();
        Excel::import($import, $request->file('import_file'));

        $message = "Successfully imported {$import->getSuccessCount()} new areas. ";
        $message .= "Updated {$import->getUpdatedCount()} existing areas.";

        if ($import->getFailCount() > 0) {
            $message .= " {$import->getFailCount()} rows failed.";
        }

        ActivityLogger::UserLog('Imported/updated areas from Excel file');

        return response()->json([
            'success' => true,
            'message' => $message,
            'stats' => [
                'created' => $import->getSuccessCount(),
                'updated' => $import->getUpdatedCount(),
                'failed' => $import->getFailCount()
            ],
            'failed_rows' => $import->getFailedRows()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error during import: ' . $e->getMessage()
        ]);
    }
}

public function export(Request $request)
{
    if (!ActivityLogger::hasPermission('locations', 'view')) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized action.'
        ], 403);
    }

    try {
        $areas = Area::with('state')->get();
        $exportData = $areas->map(function ($area) {
            return [
                'State Name' => $area->state ? $area->state->state : 'N/A',
                'Area' => $area->area,
                'Shipping Cost' => $area->shipping ?? 'N/A',
            ];
        })->toArray();

        $filename = 'areas_export_' . now()->format('Ymd_His') . '.xlsx';

        ActivityLogger::UserLog('Exported areas to Excel file');

        return Excel::download(new \App\Exports\AreasExport($exportData), $filename);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error during export: ' . $e->getMessage()
        ], 500);
    }
}
}
