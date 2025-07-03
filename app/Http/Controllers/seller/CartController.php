<?php

namespace App\Http\Controllers\seller;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\productVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\ProductStockBatch;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('cart', 'view')) {
            if ($request->ajax()) {
                if (Auth::user()->role == 'seller') {
                    $data = Cart::where('seller_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
                } else {
                    $data = Cart::where('sub_seller_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
                }
                return Datatables::of($data)
                    ->addColumn('productName', function ($data) {
                        $product = Product::where('id', $data->product_id)->first();
                        $productName = ucfirst($product->product_name);

                        $variationText = '';

                        if (!empty($data->product_variation_id)) {
                            $variation = \App\Models\productVariation::find($data->product_variation_id);

                            if ($variation) {

                                $variationText =  $variation->variation_name . ': ' . $variation->variation_value;
                            }
                        }
                        if (!empty($variationText)) {
                            return $productName . '<br><small>' . $variationText . '</small>';
                        } else {
                            return $productName;
                        }
                    })
                    ->addColumn('action', function ($data) {
                        $action = '';
                        if (ActivityLogger::hasSellerPermission('cart', 'edit')) {
                            $action .= '<a href="#" class=" edit btn btn-sm btn-info" data-id="' . $data->id . '" data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">
                        <i style="font-size: 16px; padding: 0;" class="fa-solid fa-pen-to-square"></i>
                    </a>';
                        }
                        if (ActivityLogger::hasSellerPermission('cart', 'delete')) {
                            $action .= '
                    <a onclick="deleteItem(' . $data->id . ')" class="btn btn-danger btn-sm" style="margin-right: 5px;">
                       <i style="font-size: 16px; padding: 0;" class="fa-regular fa-trash-can"></i>
                    </a>';
                        }

                        return $action;
                    })
                    ->rawColumns(['productName', 'action'])
                    ->make(true);
            }
            ActivityLogger::UserLog(Auth::user()->name . ' Open cart');
            return view('seller.pages.products.cart');
        }
    }
    public function add_to_cart(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('cart', 'add')) {
            if (empty($request->product_id)) {
                return response()->json(2);
            }
            if (empty($request->quantity) || $request->quantity < 1) {
                return response()->json(3);
            }
            if ($request->product_variation_id && !productVariation::where('id', $request->product_variation_id)->exists()) {
                return response()->json(4);
            }
            if ($request->product_batch_id && !ProductStockBatch::where('id', $request->product_batch_id)->exists()) {
                return response()->json(9);
            }
            $product = Product::find($request->product_id);
            if (!$product) {
                return response()->json(5);
            }
            $quantity = (int) $request->quantity;
            $productStockBatch =  ProductStockBatch::where('id', $request->product_batch_id)->first();

            if ((int) $productStockBatch->quantity < $quantity) {
                return response()->json(6);
            }
            $productStockBatch->quantity -= $quantity;
            $productStockBatch->save();

            if (Auth::user()->role == 'seller') {
                $seller_id = Auth::user()->id;
                $sub_seller_id = Auth::user()->id;
            } else {
                $seller_id = Auth::user()->seller_id;
                $sub_seller_id = Auth::user()->id;
            }
            Cart::create([
                'seller_id'       => $seller_id,
                'sub_seller_id'  => $sub_seller_id,
                'product_id' => $product->id,
                'batch_id' => $request->product_batch_id,
                'product_variation_id' => $request->product_variation_id,
                'quantity' => $quantity
            ]);
            ActivityLogger::UserLog(Auth::user()->name . ' Add products To cart ');
            return response()->json(1);
        }
    }
    public function update_cart(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('cart', 'edit')) {
            if (empty($request->cart_id)) {
                return response()->json(2);
            }
            if (empty($request->quantity) || $request->quantity < 1) {
                return response()->json(3);
            }
            $cartItem = Cart::find($request->cart_id);
            if (!$cartItem) {
                return response()->json(5);
            }
            $product = Product::find($cartItem->product_id);
            if (!$product) {
                return response()->json(6);
            }
            $newQuantity = (int) $request->quantity;
            $oldQuantity = $cartItem->quantity;

            $quantityDifference = $newQuantity - $oldQuantity;
            $productStockBatch =  ProductStockBatch::where('id', $cartItem->batch_id)->first();
            if ($productStockBatch->quantity < $quantityDifference) {
                return response()->json(7);
            }
            $productStockBatch->quantity -= $quantityDifference;
            $productStockBatch->save();

            $cartItem->quantity = $newQuantity;
            $cartItem->save();
            ActivityLogger::UserLog(Auth::user()->name . ' Update cart items');
            return response()->json(1);
        }
    }
    public function get_cart(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('cart', 'add')) {
            $id = $request->id;
            $Data = Cart::find($id);
            return response()->json($Data);
        }
    }
    public function delete_cart(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('cart', 'delete')) {
            $id = $request->id;
            $cartItem = Cart::find($id);
            if (!$cartItem) {
                return response()->json(2);
            }
            $product = Product::find($cartItem->product_id);
            if (!$product) {
                return response()->json(3);
            }
            $quantity = $cartItem->quantity;
            $productStockBatch =  ProductStockBatch::where('id', $cartItem->batch_id)->first();
            $productStockBatch->quantity += $quantity;
            $productStockBatch->save();

            $cartItem->delete();
            ActivityLogger::UserLog(Auth::user()->name . ' Delete cart item');
            return response()->json(1);
        }
    }
}