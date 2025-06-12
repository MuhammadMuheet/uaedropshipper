<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Order;
use App\Models\Product;
use App\Models\productVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ProductStockBatch;
class OrderController extends Controller
{
    public function order_details(Request $request,$id)
    {
        $order_id =decrypt($id);
        $order = Order::with([
            'seller' => function ($query) {
                $query->select('id', 'name', 'unique_id');
            },
            'subSeller' => function ($query) {
                $query->select('id', 'name', 'unique_id');
            },
            'state' => function ($query) {
                $query->select('id', 'state');
            },
            'area' => function ($query) {
                $query->select('id', 'area');
            },
            'orderItems.product' => function ($query) {
                $query->select('id', 'product_name', 'product_image');
            },
            'orderItems.productVariation' => function ($query) {
                $query->select('id', 'variation_name', 'variation_value', 'variation_image');
            }
        ])->find($order_id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        return view('public.pages.order.details',compact('order'));
    }
    public function order_rto_view(Request $request,$id)
    {
        if (Auth::check() && Auth::user()->role !== 'admin' && Auth::user()->role !== 'sub_admin') {
            abort(403, 'Unauthorized action.');
        }
        $order_id =decrypt($id);
        $order = Order::with([
            'seller' => function ($query) {
                $query->select('id', 'name', 'unique_id');
            },
            'subSeller' => function ($query) {
                $query->select('id', 'name', 'unique_id');
            },
            'logisticCompany' => function ($query) {
                $query->select('id', 'name');
            },
            'driver' => function ($query) {
                $query->select('id', 'name');
            },
            'state' => function ($query) {
                $query->select('id', 'state');
            },
            'area' => function ($query) {
                $query->select('id', 'area');
            },
            'orderItems.product' => function ($query) {
                $query->select('id', 'product_name', 'product_image');
            },
            'orderItems.productVariation' => function ($query) {
                $query->select('id', 'variation_name', 'variation_value', 'variation_image');
            },
            'orderItems.productStockBatch' => function ($query) {
                $query->select('id', 'regular_price', 'quantity');
            }
        ])->find($order_id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        return view('public.pages.order.order_rto',compact('order_id','order'));
    }
    public function order_rto(Request $request)
    {
        $order_id =$request->order_id;
        if (!empty($order_id)) {
            $order = Order::with('orderItems')->findOrFail($order_id);
            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }
            if ($order->status == 'Cancelled'){
            if ($order->rto_status != 'received'){
            foreach ($order->orderItems as $item) {
                $productStockBatch = ProductStockBatch::where('id', $item->batch_id)->first();
                if ($productStockBatch) {
                    $productStockBatch->quantity += $item->quantity;
                    $productStockBatch->save();
                }
            }
            }else{
                return response()->json(3);
            }
            }else{
                return response()->json(4);
            }
            $order->rto_status = $request->status;
            $order->save();
            return response()->json(1);
        }else{
            return response()->json(2);
        }
    }
        
    public function export(Request $request)
    {
        return Excel::download(new OrdersExport(), 'orders_'. time() .'.csv');
    }
    
    public function exportSelected(Request $request)
    {
        $orderIds = json_decode($request->order_ids, true);
        return Excel::download(new OrdersExport($orderIds), 'selected_orders_'. time() .'.csv');
    }

}
