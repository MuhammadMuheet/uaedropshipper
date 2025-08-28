<?php

namespace App\Http\Controllers\seller;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\productVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('products', 'view')) {
            // Eager-load relationships
            $query = Product::with([
                'variations' => function($query) {
                    $query->with(['batches' => function($q) {
                        $q->orderBy('purchase_date', 'asc');
                    }]);
                },
                'batches' => function($query) {
                    $query->orderBy('purchase_date', 'asc');
                }
            ]);

            if ($request->has('search')) {
                $search = $request->search;
                $query->where('product_name', 'LIKE', "%$search%");
            }

            $sortBy = $request->get('sort_by', 'created_at');
            $sortDirection = $request->get('sort_dir', 'desc');
            $query->orderBy($sortBy, $sortDirection);

            $products = $query->paginate(12);

            // Apply FIFO pricing logic
            $products->getCollection()->transform(function ($product) {
                if ($product->product_type === 'simple') {
                    $availableBatch = $product->batches
                        ->where('quantity', '>', 0)
                        ->sortBy('purchase_date')
                        ->first();
                        $availableQuantity = $product->batches
                        ->where('quantity', '>', 0)
                        ->sum('quantity');
                } else {
                    $availableBatch = $product->variations
                        ->flatMap->batches
                        ->where('quantity', '>', 0)
                        ->sortBy('purchase_date')
                        ->first();
                        $availableQuantity = $product->variations
                        ->flatMap->batches
                        ->where('quantity', '>', 0)
                        ->sum('quantity');
                }

                $product->fifo_price = $availableBatch ? $availableBatch->regular_price : null;
                $product->quantity = $availableQuantity ? $availableQuantity : null;
                return $product;
            });

            if ($request->ajax()) {
                return view('seller.pages.products.partials.product_list', compact('products'))->render();
            }

            ActivityLogger::UserLog(Auth::user()->name . ' open product page');
            return view('seller.pages.products.all', compact('products'));
        }
    }

    public function get_seller_product_data(Request $request)
    {
        if (!ActivityLogger::hasSellerPermission('products', 'view')) {
            return response()->json(['error' => 'Permission denied'], 403);
        }

        $id = $request->id;

        // First get the product type
        $productType = Product::where('id', $id)->value('product_type');

        if (!$productType) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        if ($productType === 'simple') {
            // Get product with batches ordered by FIFO
            $product = Product::with([
                'batches' => function ($query) {
                    $query->where('quantity', '>', 0)
                          ->orderBy('purchase_date', 'asc');
                }
            ])->find($id);

            $fifoBatch = $product->batches->first();

            return response()->json([
                'product' => $product,
                   'batch_id' => $fifoBatch->id,
                'fifo_batch_price' => $fifoBatch ? [
                    'price' => $fifoBatch->regular_price,

                    'quantity' => $fifoBatch->quantity,
                ] : null,
            ]);
        }

        if ($productType === 'variable') {
            // Get product with variations and their batches ordered by FIFO
            $product = Product::with([
                'variations.batches' => function ($query) {
                    $query->where('quantity', '>', 0)
                          ->orderBy('purchase_date', 'asc');
                }
            ])->find($id);

            $variationPrices = [];

            foreach ($product->variations as $variation) {
    $batch = $variation->batches
        ->where('quantity', '>', 0)
         ->sortBy('purchase_date')
        ->first();

    if ($batch) {
        $variationPrices[] = [
            'variation_id' => $variation->id,
            'variation_value' => $variation->variation_value,
            'price' => $batch->regular_price,
            'batch_id' => $batch->id,
            'quantity' => $batch->quantity,
            'purchase_date' => $batch->purchase_date,
            'variation_image' => $variation->variation_image,
        ];
    }
}

            return response()->json([
                'product' => $product,
                'variation_prices' => $variationPrices,
            ]);
        }

        return response()->json(['error' => 'Unknown product type'], 400);
    }

    public function get_seller_product_variation_price(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('products', 'view')) {
            $variation_id = $request->variation_id;

            $variation = productVariation::with(['batches' => function ($query) {
                $query->where('quantity', '>', 0)
                      ->orderBy('purchase_date', 'asc');
            }])->find($variation_id);

            if (!$variation) {
                return response()->json(['error' => 'Variation not found'], 404);
            }

            $firstAvailableBatch = $variation->batches->first();

            return response()->json([
                'variation_id' => $variation->id,
                'variation_value' => $variation->variation_value,
                'variation_image' => $variation->variation_image,
                'price' => $firstAvailableBatch ? $firstAvailableBatch->regular_price : null,
                'batch_id' => $firstAvailableBatch ? $firstAvailableBatch->id : null,
            ]);
        }
    }
    public function show($id)
    {
        if (!ActivityLogger::hasSellerPermission('products', 'view')) {
            abort(403, 'Unauthorized action.');
        }

        // Fetch product with relationships
        $product = Product::with([
            'variations.batches' => function ($query) {
                $query->where('quantity', '>', 0)->orderBy('purchase_date', 'asc');
            },
            'batches' => function ($query) {
                $query->where('quantity', '>', 0)->orderBy('purchase_date', 'asc');
            }
        ])->findOrFail($id);

        // Decode descriptions
        $product->product_short_des = base64_decode($product->product_short_des);
        $product->product_des = base64_decode($product->product_des);

        // Prepare gallery images
        $gallery = json_decode($product->product_gallery, true) ?? [];
        $images = [];
        if ($product->product_image) {
            $images[] = [
                'src' => asset('storage/' . $product->product_image),
                'alt' => $product->product_name . ' main image',
            ];
        }
        foreach ($gallery as $image) {
            $images[] = [
                'src' => asset('storage/' . $image),
                'alt' => $product->product_name . ' gallery image',
            ];
        }

        // Prepare variations and stock
        $variations = [];
        $totalStock = 0;
        $defaultPrice = null;
        $defaultBatchId = null;

        if ($product->product_type === 'simple') {
            $batch = $product->batches->first();
            $totalStock = $product->batches->sum('quantity');
            $defaultPrice = $batch ? $batch->regular_price : null;
            $defaultBatchId = $batch ? $batch->id : null;
        } else {
            foreach ($product->variations as $index => $variation) {
                $batch = $variation->batches->first();
                $varStock = $variation->batches->sum('quantity');
                $variations[] = [
                    'id' => $variation->id,
                    'name' => $variation->variation_name,
                    'value' => $variation->variation_value,
                    'image' => $variation->variation_image ? asset('storage/' . $variation->variation_image) : ($product->product_image ? asset('storage/' . $product->product_image) : asset('images/product.jpg')),
                    'stock' => $varStock,
                    'price' => $batch ? $batch->regular_price : null,
                    'batch_id' => $batch ? $batch->id : null,
                ];
                $totalStock += $varStock;
                if ($index === 0) {
                    $defaultPrice = $batch ? $batch->regular_price : null;
                    $defaultBatchId = $batch ? $batch->id : null;
                }
            }
        }

        ActivityLogger::UserLog(Auth::user()->name . ' viewed product detail page for: ' . $product->product_name);

        return view('seller.pages.products.product_details', compact('product', 'images', 'variations', 'totalStock', 'defaultPrice', 'defaultBatchId'));
    }
}
