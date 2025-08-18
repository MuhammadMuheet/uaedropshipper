<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\productVariation;
use App\Models\SubCategory;
use App\Models\ProductStockBatch;
use App\Traits\imageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    use imageUploadTrait;
    public function index(Request $request) {
        if (ActivityLogger::hasPermission('products', 'view')) {
            if ($request->ajax()) {
                $data = Product::orderBy('id', 'DESC')->get();
                return Datatables::of($data)
                    ->addColumn('statusView', function($data) {
                        if ($data->status == 'active') {
                            return "<div class='badge bg-success'>Active</div>";
                        } else {
                            return "<div class='badge bg-danger'>Blocked</div>";
                        }
                    })
                    ->addColumn('productImage', function($data) {
                        if (!empty($data->product_image)){
                            return '<a href="'.asset('storage/'.$data->product_image).'" data-lightbox="roadtrip"><img src="'.asset('storage/'.$data->product_image).'" data-src="'.asset('storage/'.$data->product_image).'" class="w-25 h-auto">  </a>';
                        }else{
                            return 'N/A';
                        }
                    })
                    ->addColumn('productName', function($data) {
                        if (!empty($data->product_name)){
                            return ucfirst($data->product_name);
                        }else{
                            return 'N/A';
                        }
                    })
                    ->addColumn('productSku', function($data) {
                        if (!empty($data->product_sku)){
                            return '<a href="#" class=" view " data-id="'.$data->id.'" data-bs-toggle="modal" data-bs-target="#edit_kt_modal_new_target">'.ucfirst($data->product_sku).'</a>';
                        }else{
                            return 'N/A';
                        }
                    })
                    ->addColumn('productType', function($data) {
                        if (!empty($data->product_type)){
                            return ucfirst($data->product_type);
                        }else{
                            return 'N/A';
                        }
                    })
                    ->addColumn('action', function($data) {
                        if (ActivityLogger::hasPermission('products', 'status')) {
                            $action = '';
                            if ($data->status == 'block') {
                                $action = '<a onclick="changeStatus(1, ' . $data->id . ')" class="btn btn-success btn-sm me-2">
                    <i style="font-size: 16px; padding: 0;" class="fa-solid fa-unlock"></i>
               </a>';
                            } else {
                                $action = '<a onclick="changeStatus(0, ' . $data->id . ')" class="btn btn-danger btn-sm me-2">
                    <i style="font-size: 16px; padding: 0;" class="fa-solid fa-lock"></i>
               </a>';
                            }
                        }else{
                            $action ='';
                        }
                        if (ActivityLogger::hasPermission('products', 'edit')) {
                            $action .='<a href="'.route('update_product',\Illuminate\Support\Facades\Crypt::encrypt($data->id)).'" class=" edit btn btn-sm btn-info" >
                        <i style="font-size: 16px; padding: 0;" class="fa-solid fa-pen-to-square"></i>
                    </a>';
                        }
                        if (ActivityLogger::hasPermission('products', 'delete')) {
                            $action .='
                    <a onclick="deleteItem(' . $data->id . ')" class="btn btn-danger btn-sm" style="margin-right: 5px;">
                       <i style="font-size: 16px; padding: 0;" class="fa-regular fa-trash-can"></i>
                    </a>';
                        }
                        return $action;
                    })
                    ->rawColumns(['statusView','productImage','productName','productSku','productType', 'action'])
                    ->make(true);
            }
            ActivityLogger::UserLog('Open All products page');
            return view('admin.pages.products.all_products');
        }
    }
    public function all_product_stocks(Request $request)
    {
        if (ActivityLogger::hasPermission('products', 'view')) {
            if ($request->ajax()) {
             $simpleProducts = DB::table('products as p')
        ->join('product_stock_batches as psb', 'p.id', '=', 'psb.product_id')
        ->select(
            'psb.product_id as id',
            'p.product_name',
            DB::raw('NULL as variation_name'),
            DB::raw('SUM(psb.quantity) as total_quantity'),
            DB::raw('AVG(psb.purchase_price) as avg_purchase_price'),
            DB::raw('AVG(psb.regular_price) as avg_sale_price'),
            DB::raw('SUM(psb.purchase_price * psb.quantity) as total_purchase_price'),
            DB::raw('SUM(psb.regular_price * psb.quantity) as total_sale_price'),
            'p.product_type',
            'p.product_sku',
            'p.status'
        )
        ->where('p.product_type', 'simple')
        ->groupBy(
            'psb.product_id',
            'p.product_name',
            'p.product_type',
            'p.product_sku',
            'p.status'
        );

    // Variable products query
    $variableProducts = DB::table('products as p')
        ->join('product_variations as pv', 'p.id', '=', 'pv.product_id')
        ->join('product_stock_batches as psb', 'pv.id', '=', 'psb.product_variation_id')
        ->select(
            'psb.product_id as id',
            'p.product_name',
            DB::raw('CONCAT(pv.variation_name, " : ", pv.variation_value) as variation_name'),
            DB::raw('SUM(psb.quantity) as total_quantity'),
            DB::raw('AVG(psb.purchase_price) as avg_purchase_price'),
            DB::raw('AVG(psb.regular_price) as avg_sale_price'),
            DB::raw('SUM(psb.purchase_price * psb.quantity) as total_purchase_price'),
            DB::raw('SUM(psb.regular_price * psb.quantity) as total_sale_price'),
            'p.product_type',
            'p.product_sku',
            'p.status'
        )
        ->where('p.product_type', 'variable')
        ->groupBy(
            'psb.product_id',
            'p.product_name',
            'pv.variation_name',
            'pv.variation_value',
            'p.product_type',
            'p.product_sku',
            'p.status'
        );

    if (!empty($request->product)) {
        $simpleProducts->where('p.id', $request->product);
        $variableProducts->where('p.id', $request->product);
    }

    if (!empty($request->product_variation)) {
        $variableProducts->where('pv.id', $request->product_variation);
    }

                $query = $simpleProducts->unionAll($variableProducts);
                $results = $query->get();
                $totalPurchasePrice = 0;
                $totalSalePrice = 0;
                $totalQuantity = 0;
                foreach ($results as $item) {
                    $totalPurchasePrice += $item->total_purchase_price;
                    $totalSalePrice += $item->total_sale_price;
                    $totalQuantity += $item->total_quantity;
                }
                return DataTables::of($query)
                    ->addColumn('productName', function ($row) {
                        $name = ucfirst($row->product_name);
                        return $row->variation_name ? "$name [{$row->variation_name}]" : $name;
                    })

                    ->addColumn('productType', function ($row) {
                        return ucfirst($row->product_type ?? 'N/A');
                    })
                    ->addColumn('quantity', function ($row) {
                        return $row->total_quantity ?? 0;
                    })
                    ->addColumn('avgPurchase', function ($row) {
                        return number_format($row->avg_purchase_price, 2);
                    })
                    ->addColumn('avgSale', function ($row) {
                        return number_format($row->avg_sale_price, 2);
                    })
                    ->addColumn('totalPurchase', function ($row) {
                        return number_format($row->total_purchase_price, 2);
                    })
                    ->addColumn('totalSale', function ($row) {
                        return number_format($row->total_sale_price, 2);
                    })
                    ->with('totalPurchasePrice', $totalPurchasePrice)
                    ->with('totalSalePrice', $totalSalePrice)
                    ->with('totalQuantity', $totalQuantity)
                    ->rawColumns([ 'productName', 'productType', 'totalPurchase', 'totalSale'])
                    ->make(true);
            }

            ActivityLogger::UserLog('Open All product Stocks page');
            $products = Product::where('status', 'active')->get();
            return view('admin.pages.products.stock',compact('products'));
        }
    }
    public function get_admin_product_variation_stock(Request $request)
    {
        if (ActivityLogger::hasPermission('products', 'view')) {
            $product = Product::find($request->product_id);
            $productVariations = productVariation::where('product_id','=',$request->product_id)->get();
            $options = '<option value="" disabled selected>Choose Product Variation</option>';
            foreach ($productVariations as $productVariation) {
                $options .= '<option value="' . $productVariation->id . '">' . $product->product_name.' [ '.$productVariation->variation_name .':'.$productVariation->variation_value.' ] '. '</option>';
            }
            return response()->json(['options' => $options]);
        }
    }
    public function get_product(Request $request) {
        if (ActivityLogger::hasPermission('products', 'views')) {
            $id = $request->id;
            $product = Product::find($id);

            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            $product_variations = productVariation::where('product_id', $id)->get();

            return response()->json([
                'product' => $product,
                'product_variations' => $product_variations,
            ]);
        }

        return response()->json(['error' => 'Unauthorized access'], 403);
    }

    public function add_product() {
        if (ActivityLogger::hasPermission('products', 'add')) {
            $categories = Category::where('status','=','active')->get();
            ActivityLogger::UserLog('Open Add product page');

            return view('admin.pages.products.add_product', compact('categories',));
    }
    }
    public function add_product_store(Request $request) {
        if (!ActivityLogger::hasPermission('products', 'add')) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        // Basic validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category' => 'required',
            'sub_category' => 'required',
            'product_sku' => 'required',
            'product_short_des' => 'required',
            'product_type' => 'required|in:simple,variable',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Please fill all required fields']);
        }

        try {
            DB::beginTransaction();

            // Handle main product image
            $imagePath = $request->file('product_img')->store('products', 'public');

            // Handle gallery images
            $galleryPaths = [];
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $image) {
                    $galleryPaths[] = $image->store('products/gallery', 'public');
                }
            }

            // Create the main product
            $product = Product::create([
                'product_name' => $request->name,
                'category_id' => $request->category,
                'sub_category_id' => $request->sub_category,
                'product_sku' => $request->product_sku,
                'product_short_des' => base64_encode($request->product_short_des),
                'product_des' => base64_encode($request->product_des),
                'product_image' => $imagePath,
                'product_gallery' => json_encode($galleryPaths),
                'product_type' => $request->product_type,
            ]);

            if ($request->product_type === 'simple') {
                // Handle simple product batches
                if (empty($request->simple_batch_name)) {
                    throw new \Exception('At least one stock batch is required for simple products');
                }

                $totalStock = 0;
                $totalValue = 0;
                $regularPrices = [];

                foreach ($request->simple_batch_name as $index => $batchName) {
                    // Handle receipt image if exists
                    $receiptPath = null;
                    if (isset($request->file('simple_batch_receipt')[$index])) {
                        $receiptPath = $request->file('simple_batch_receipt')[$index]->store('batch_receipts', 'public');
                    }

                    // Create batch (product_variation_id will be null for simple products)
                    ProductStockBatch::create([
                        'product_id' => $product->id,
                        'product_variation_id' => null,
                        'batch_name' => $batchName,
                        'purchase_price' => $request->simple_batch_price[$index],
                        'regular_price' => $request->reg_batch_price[$index] ?? null,
                        'quantity' => $request->simple_batch_quantity[$index],
                        'purchase_date' => $request->simple_batch_date[$index],
                        'receipt_image' => $receiptPath,
                    ]);

                    // Calculate totals
                    $totalStock += $request->simple_batch_quantity[$index];
                    $totalValue += $request->simple_batch_quantity[$index] * $request->simple_batch_price[$index];

                    // Collect regular prices
                    if (!empty($request->reg_batch_price[$index])) {
                        $regularPrices[] = $request->reg_batch_price[$index];
                    }
                }

            } elseif ($request->product_type === 'variable') {
                // Handle variable products and their batches
                if (empty($request->variation_product)) {
                    throw new \Exception('At least one variation is required for variable products');
                }

                foreach ($request->variation_product as $varIndex => $variationName) {
                    // Handle variation image
                    $variationImagePath = null;
                    if (isset($request->file('variation_img')[$varIndex])) {
                        $variationImagePath = $request->file('variation_img')[$varIndex]->store('products/variations', 'public');
                    }

                    // Create variation
                    $variation = productVariation::create([
                        'product_id' => $product->id,
                        'variation_name' => $variationName,
                        'variation_value' => $request->variation_product_value[$varIndex] ?? null,
                        'variation_image' => $variationImagePath,
                        'variation_sku' => $request->variation_product_sku[$varIndex] ?? null,
                    ]);

                    // Handle variation batches
                    $batchNames = $request->input("variation_{$varIndex}_batch_name", []);
                    if (empty($batchNames)) {
                        throw new \Exception('At least one stock batch is required for each variation');
                    }

                    $varTotalStock = 0;
                    $varTotalValue = 0;
                    $varRegularPrices = [];

                    foreach ($batchNames as $batchIndex => $batchName) {
                        // Handle receipt image if exists
                        $receiptPath = null;
                        if (isset($request->file("variation_{$varIndex}_batch_receipt")[$batchIndex])) {
                            $receiptPath = $request->file("variation_{$varIndex}_batch_receipt")[$batchIndex]->store('batch_receipts', 'public');
                        }

                        // Create variation batch (with product_variation_id)
                        ProductStockBatch::create([
                            'product_id' => $product->id,
                            'product_variation_id' => $variation->id,
                            'batch_name' => $batchName,
                            'purchase_price' => $request->input("variation_{$varIndex}_batch_price")[$batchIndex],
                            'regular_price' => $request->input("variation_{$varIndex}_batch_reg_price")[$batchIndex] ?? null,
                            'quantity' => $request->input("variation_{$varIndex}_batch_quantity")[$batchIndex],
                            'purchase_date' => $request->input("variation_{$varIndex}_batch_date")[$batchIndex],
                            'receipt_image' => $receiptPath,
                        ]);

                        // Calculate variation totals
                        $varTotalStock += $request->input("variation_{$varIndex}_batch_quantity")[$batchIndex];
                        $varTotalValue += $request->input("variation_{$varIndex}_batch_quantity")[$batchIndex] *
                                         $request->input("variation_{$varIndex}_batch_price")[$batchIndex];

                        // Collect regular prices
                        if (!empty($request->input("variation_{$varIndex}_batch_reg_price")[$batchIndex])) {
                            $varRegularPrices[] = $request->input("variation_{$varIndex}_batch_reg_price")[$batchIndex];
                        }
                    }

                }
            }

            DB::commit();
            ActivityLogger::UserLog('Added product: ' . $request->name);

            return response()->json([
                'status' => 'success',
                'message' => 'Product added successfully',
                'redirect' => route('all_products')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function update_product($id)
{
    $product_id = decrypt($id);
    if (ActivityLogger::hasPermission('products', 'edit')) {
        $product = Product::with(['variations.batches', 'batches'])->findOrFail($product_id);
        $categories = Category::where('status','=','active')->get();
        $subCategories = SubCategory::where('category_id', $product->category_id)->get();
        $images = [];
        $imagePaths = json_decode($product->product_gallery);
        $images[] = [
            'id' => $product->id,
            'image_paths' => $imagePaths,
        ];
        ActivityLogger::UserLog('Open Update product page ' . $product->name);
        return view('admin.pages.products.edit_product', compact('id','images','product', 'categories', 'subCategories'));
    }
    abort(403, 'Unauthorized action.');
}
public function update_product_store(Request $request)
{
    // Validate the request
    $validator = Validator::make($request->all(), [
        'product_id' => 'required|exists:products,id',
        'name' => 'required|string|max:255',
        'category' => 'required|exists:categories,id',
        'sub_category' => 'nullable|exists:sub_categories,id',
        'product_sku' => 'required|string|max:255|unique:products,product_sku,' . $request->product_id,
        'product_short_des' => 'required|string',
        'product_des' => 'nullable|string',
        'product_type' => 'required|in:simple,variable',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => $validator->errors()->first()
        ], 422);
    }

    DB::beginTransaction();

    try {
        $product = Product::findOrFail($request->product_id);

        // Update basic product info
        $product->product_name = $request->name;
        $product->category_id = $request->category;
        $product->sub_category_id = $request->sub_category;
        $product->product_sku = $request->product_sku;
        $product->product_short_des = base64_encode($request->product_short_des);
        $product->product_des = base64_encode($request->product_des);
        $product->product_type = $request->product_type;

        // Handle main product image
        if ($request->hasFile('product_img')) {
            // Delete old image if exists
            if ($product->product_image) {
                Storage::delete($product->product_image);
            }

            $imagePath = $request->file('product_img')->store('products', 'public');
            $product->product_image = $imagePath;
        }

        $product->save();

        $existingGallery = json_decode($product->product_gallery, true) ?? [];
        $newGallery = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $image) {
                $newGallery[] = $image->store('products/gallery', 'public');
            }
        }
        $mergedGallery = array_merge($existingGallery, $newGallery);
        $product->product_gallery = json_encode($mergedGallery);
        $product->save();
        // Handle product type specific data
        if ($request->product_type == 'simple') {

            $this->handleSimpleProduct($request, $product);
        } else {

            $this->handleVariableProduct($request, $product);
        }

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully!'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong: ' . $e->getMessage()
        ], 500);
    }
}

private function handleSimpleProduct(Request $request, Product $product)
{
    // First, delete all variations if any exist (converting from variable to simple)
    $product->variations()->delete();

    // Get existing batch IDs to track which ones are being updated
    $existingBatchIds = $product->batches()->pluck('id')->toArray();
    $updatedBatchIds = [];

    // Process batches
    if ($request->has('simple_batch_id')) {
        foreach ($request->simple_batch_id as $index => $batchId) {
            $batchData = [
                'batch_name' => $request->simple_batch_name[$index],
                'regular_price' => $request->reg_batch_price[$index] ?? 0,
                'purchase_price' => $request->simple_batch_price[$index],
                'quantity' => $request->simple_batch_quantity[$index],
                'purchase_date' => $request->simple_batch_date[$index],
            ];

            // Handle receipt image if uploaded
            if ($request->hasFile("simple_batch_receipt.$index")) {
                @$receiptPath = $request->file("simple_batch_receipt.$index")->store('products/receipts', 'public');
                $batchData['receipt_image'] = @$receiptPath;
            }

            if ($batchId) {
                // Update existing batch
                $batch = ProductStockBatch::find($batchId);
                if ($batch) {
                    $batch->update($batchData);
                    $updatedBatchIds[] = $batchId;
                }
            } else {
                // Create new batch
                $batch = $product->batches()->create($batchData);
                $updatedBatchIds[] = $batch->id;
            }
        }
    }

    // Delete batches that weren't included in the update
    $batchesToDelete = array_diff($existingBatchIds, $updatedBatchIds);
    if (!empty($batchesToDelete)) {
        ProductStockBatch::whereIn('id', $batchesToDelete)->delete();
    }
}

private function handleVariableProduct(Request $request, Product $product)
{

    // First, delete all simple batches if any exist (converting from simple to variable)
    //$product->batches()->delete();

    // Get existing variation IDs to track which ones are being updated
    $existingVariationIds = $product->variations()->pluck('id')->toArray();

    $updatedVariationIds = [];

    if ($request->has('variation_id')) {
        foreach ($request->variation_id as $varIndex => $variationId) {
            $variationData = [
                'variation_name' => $request->variation_product[$varIndex],
                'variation_value' => $request->variation_product_value[$varIndex],
                'variation_sku' => $request->variation_product_sku[$varIndex],
            ];

            // Handle variation image if uploaded
            if ($request->hasFile("variation_img.$varIndex")) {
                $variationImagePath = $request->file("variation_img.$varIndex")->store('products/variations', 'public');
                $variationData['variation_image'] = $variationImagePath;
            }

            if ($variationId) {
                // Update existing variation
                $variation = productVariation::find($variationId);
                if ($variation) {
                    $variation->update($variationData);
                    $updatedVariationIds[] = $variationId;

                    // Process batches for this variation
                    $this->handleVariationBatches($request, $variation, $varIndex);
                }
            } else {
                // Create new variation
                $variation = $product->variations()->create($variationData);
                $updatedVariationIds[] = $variation->id;

                // Process batches for this variation
                $this->handleVariationBatches($request, $variation, $varIndex);
            }
        }
    }

    // Delete variations that weren't included in the update
    $variationsToDelete = array_diff($existingVariationIds, $updatedVariationIds);
    if (!empty($variationsToDelete)) {
        productVariation::whereIn('id', $variationsToDelete)->delete();
    }
}

private function handleVariationBatches(Request $request, productVariation $variation, $varIndex)
{
    $existingBatchIds = $variation->batches()->pluck('id')->toArray();
    $updatedBatchIds = [];

    $batchNames = $request->input("variation_{$varIndex}_batch_name", []);
    $batchPrices = $request->input("variation_{$varIndex}_batch_price", []);
    $batchQuantities = $request->input("variation_{$varIndex}_batch_quantity", []);
    $batchDates = $request->input("variation_{$varIndex}_batch_date", []);
    $batchRegPrices = $request->input("variation_{$varIndex}_batch_reg_price", []);
    $batchIds = $request->input("variation_{$varIndex}_batch_id", []);

    foreach ($batchNames as $batchIndex => $batchName) {
        $batchData = [
            'batch_name' => $batchName,
            'regular_price' => $batchRegPrices[$batchIndex] ?? 0,
            'purchase_price' => $batchPrices[$batchIndex],
            'quantity' => $batchQuantities[$batchIndex],
            'purchase_date' => $batchDates[$batchIndex],
        ];

        // Handle receipt image if uploaded
        $receiptKey = "variation_{$varIndex}_batch_receipt.$batchIndex";
        if ($request->hasFile($receiptKey)) {
            @$receiptPath = $request->file($receiptKey)->store('products/receipts', 'public');
            $batchData['receipt_image'] = @$receiptPath;
        }

        if (!empty($batchIds[$batchIndex])) {
            // Update existing batch
            $batch = ProductStockBatch::find($batchIds[$batchIndex]);
            if ($batch) {
                $batch->update($batchData);
                $updatedBatchIds[] = $batch->id;
            }
        } else {
            if($request->hasFile($receiptKey)) {
                @$receiptPath = $request->file($receiptKey)->store('products/receipts', 'public');
            }
            // Create new batch
            $batch = ProductStockBatch::create([
                'product_id' => $variation->product_id,
                'product_variation_id' => $variation->id,
                'batch_name' => $batchName,
                'regular_price' => $batchRegPrices[$batchIndex] ?? 0,
                'purchase_price' => $batchPrices[$batchIndex],
                'quantity' => $batchQuantities[$batchIndex],
                'purchase_date' => $batchDates[$batchIndex],
                'receipt_image' => @$receiptPath,
            ]);

            $updatedBatchIds[] = $batch->id;
        }
    }

    // Delete batches that weren't included in the update
    $batchesToDelete = array_diff($existingBatchIds, $updatedBatchIds);
    if (!empty($batchesToDelete)) {
        ProductStockBatch::whereIn('id', $batchesToDelete)->delete();
    }
}


    public function delete_product(Request $request)
    {
        if (ActivityLogger::hasPermission('products', 'delete')) {
            $id=$request->id;
            $detail = Product::find($id);
            Product::destroy($detail->id);
            ActivityLogger::UserLog('Delete Product '.$detail->product_sku);
            echo 1;
            exit();
        }
    }
    public function delete_product_img(Request $request) {
        $id = $request->id;
        $p_img = $request->p_img;
        $p_img_details = Product::find($id);
        if (!$p_img_details) {
            return response()->json(['success' => false]);
        }
        $imagePaths = json_decode($p_img_details->product_gallery);
        if (($key = array_search($p_img, $imagePaths)) !== false) {
            unset($imagePaths[$key]);
            $p_img_details->product_gallery = json_encode(array_values($imagePaths));
            $p_img_details->save();
            Storage::delete('public/products/variations/' . $p_img);
            ActivityLogger::UserLog('Delete product image' .$p_img);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
    public function status_product(Request $request)
    {
        if (ActivityLogger::hasPermission('products', 'status')) {
            $id=$request->id;
            $status=$request->status;
            $detail = Product::find($id);
            if ($detail) {
                if ($status == '1') {
                    $updated = $detail->update(['status' => 'active']);
                    ActivityLogger::UserLog('Update Product '.$detail->product_sku.' Status to Active');
                    echo 2;
                    exit();
                } else {
                    $updated = $detail->update(['status' => 'block']);
                    ActivityLogger::UserLog('Update Product '.$detail->product_sku.' Status to Block');
                    echo 1;
                    exit();
                }
            }
        }
    }
    public function get_sub_category_for_products(Request $request)
    {
        $sub_categories = SubCategory::where('category_id','=',$request->category_id)->get();
        $options = '<option value="" disabled selected>Choose Sub Category</option>';
        foreach ($sub_categories as $sub_category) {
                    $options .= '<option value="' . $sub_category->id . '">' . $sub_category->sub_category . '</option>';
        }
        return response()->json(['options' => $options]);
    }
}
