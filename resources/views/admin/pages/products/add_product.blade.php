@extends('admin.layouts.app')
@section('title', 'Add Product')

@push('css')
    <style>
        .select2-selection {
            background-color: #f5f8fa;
            border-color: #f5f8fa;
            color: #5e6278;
            padding: .75rem 1rem;
            font-size: 1.1rem;
            font-weight: 500;
            line-height: 1.5;
            background-clip: padding-box;
            appearance: none;
            border-radius: .475rem;
        }
        .batch-section {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
            border: 1px dashed #ddd;
        }
        .batch-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .stock-summary {
            background: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            box-shadow: 0 0 5px rgba(0,0,0,0.05);
        }
    </style>
@endpush

@section('content')
    @php
        use App\Helpers\ActivityLogger;
        if (!ActivityLogger::hasPermission('products','add')){
               abort(403, 'Unauthorized action.');
        }
    @endphp
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Add New Product</h1>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="flex-lg-auto min-w-lg-300px">
                    <div class="card">
                        <div class="row" style="padding: 2rem 2.25rem;">
                            <div class="col-md-6 mt-3">
                                <h6 class="title mt-5">Add</h6>
                            </div>
                            <div class="col-md-6 mt-3 text-md-end text-center">
                                <a href="{{route('all_products')}}" class="btn btn-primary  me-4">
                                    <span class="btn-text-inner">Go Back</span>
                                </a>
                            </div>
                        </div>
                        <div class="separator separator-dashed"></div>
                        <form method="post" id="add_product" class="profile-form" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 mt-3">
                                        <label class="form-label">Product Name</label>
                                        <input type="text" class="form-control form-control-solid required-input" name="name" id="name" placeholder="Product Name">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                            <span class="required">Choose Category</span>
                                        </label>
                                        <select class="form-select form-select-solid js-example-basic-single" id="category" name="category" onchange="get_sub_category(this.value)">
                                            <option value="" selected disabled>Choose Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}">{{$category->category}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                            <span class="required">Choose Sub Category</span>
                                        </label>
                                        <select class="form-select form-select-solid js-example-basic-single1" id="sub_category" name="sub_category">
                                            <option value="" selected disabled>Choose Sub Category</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label class="form-label">Product SKU</label>
                                        <input type="text" class="form-control form-control-solid w-100 required-input" name="product_sku" id="product_sku" placeholder="Enter Product SKU">
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label class="form-label">Product Short Description</label>
                                        <textarea class="form-control form-control-solid w-100 required-input" name="product_short_des" id="product_short_des"></textarea>
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label class="form-label">Product Description</label>
                                        <textarea class="form-control form-control-solid w-100" name="product_des" id="product_des"></textarea>
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <label class="form-label">Product Image</label>
                                        <input type="file" class="form-control form-control-solid w-100 required-input" name="product_img" id="product_img">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12 mt-3">
                                        <label for="upload_file" class="form-label">Product Gallery</label>
                                        <div id="createInput"></div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12 mt-3">
                                        <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                            <span class="required">Choose Product Type</span>
                                        </label>
                                        <select class="form-select form-select-solid required-input" id="product_type" name="product_type" onchange="get_product_type(this.value)">
                                            <option value="" selected disabled>Choose Product Type</option>
                                            <option value="simple">Simple Product</option>
                                            <option value="variable">Variable product</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Simple Product Section -->
                                <div id="simple_product" style="display: none;">
                                    <!-- Simple Product Stock Batches -->
                                    <div class="batch-section mt-4" id="simple_product_batches">
                                        <div class="batch-header">
                                            <h4>Stock Batches</h4>
                                            <p class="text-muted">Add different stock batches with purchase prices</p>
                                        </div>

                                        <div id="simple_batches_container">
                                            <div class="row batch-item mb-3">
                                                <div class="col-md-12 mt-3">
                                                    <label class="form-label">Batch Name</label>
                                                    <input type="text" class="form-control form-control-solid batch-name" name="simple_batch_name[]" readonly>
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <label class="form-label">Regular Price</label>
                                                    <input type="number" step="0.01" class="form-control form-control-solid w-100" name="reg_batch_price[]" id="reg_batch_price" placeholder="Enter Regular Price">
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <label class="form-label">Purchase Price</label>
                                                    <input type="number" step="0.01" class="form-control form-control-solid batch-price" name="simple_batch_price[]" required placeholder="Enter Purchase Price">
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <label class="form-label">Quantity</label>
                                                    <input type="number" class="form-control form-control-solid batch-quantity" name="simple_batch_quantity[]" required placeholder="Enter Quantity">
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <label class="form-label">Purchase Date</label>
                                                    <input type="date" class="form-control form-control-solid" name="simple_batch_date[]" required placeholder="Enter Purchase Date">
                                                </div>
                                                <div class="col-md-12 mt-3">
                                                    <label class="form-label">Receipt (Optional)</label>
                                                    <input type="file" class="form-control form-control-solid" name="simple_batch_receipt[]">
                                                </div>
                                                <div class="col-md-12 mt-3 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger remove-batch" disabled><i style="font-size: 16px; padding: 0;" class="fa-regular fa-trash-can"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <button type="button" class="btn btn-primary" id="add_simple_batch">Add Batch</button>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <div class="stock-summary">
                                                    <h5>Stock Summary</h5>
                                                    <p>Total Stock: <span id="simple_total_stock">0</span></p>
                                                    <p>Average Price: $<span id="simple_avg_price">0.00</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Variable Product Section -->
                                <div id="variable_product" style="display: none;">
                                    <div id="variable_product_container">
                                        <div class="row mt-3 variation_item">
                                            <div class="col-sm-12 mt-3">
                                                <h4>Variation #1</h4>
                                            </div>
                                            <div class="col-sm-6 mt-3">
                                                <label class="form-label">Product Variation</label>
                                                <input type="text" class="form-control form-control-solid w-100" name="variation_product[]" placeholder="Enter Product Variation">
                                            </div>
                                            <div class="col-sm-6 mt-3">
                                                <label class="form-label">Product Variation Value</label>
                                                <input type="text" class="form-control form-control-solid w-100" name="variation_product_value[]" placeholder="Enter Product Variation Value">
                                            </div>
                                            <div class="col-sm-6 mt-3">
                                                <label class="form-label">Variation Photo</label>
                                                <input type="file" class="form-control form-control-solid w-100" name="variation_img[]">
                                            </div>
                                            <div class="col-sm-6 mt-3">
                                                <label class="form-label">Product Variation SKU</label>
                                                <input type="text" class="form-control form-control-solid w-100" name="variation_product_sku[]" placeholder="Enter Product Variation SKU">
                                            </div>

                                            <!-- Variation Batches -->
                                            <div class="col-12 mt-4 variation-batches" id="variation_0_batches">
                                                <div class="batch-section">
                                                    <div class="batch-header">
                                                        <h5>Stock Batches for this Variation</h5>
                                                    </div>

                                                    <div id="variation_0_batches_container">
                                                        <div class="row batch-item mb-3">
                                                            <div class="col-md-12 mt-3">
                                                                <label class="form-label">Batch Name</label>
                                                                <input type="text" class="form-control form-control-solid batch-name" name="variation_0_batch_name[]" readonly>
                                                            </div>
                                                            <div class="col-md-6 mt-3">
                                                                <label class="form-label">Regular Price</label>
                                                                <input type="number" step="0.01" class="form-control form-control-solid w-100" name="variation_0_batch_reg_price[]" placeholder="Enter Regular Price">
                                                            </div>
                                                            <div class="col-md-6 mt-3">
                                                                <label class="form-label">Purchase Price</label>
                                                                <input type="number" step="0.01" class="form-control form-control-solid batch-price" name="variation_0_batch_price[]" required placeholder="Enter Purchase Price">
                                                            </div>
                                                            <div class="col-md-6 mt-3">
                                                                <label class="form-label">Quantity</label>
                                                                <input type="number" class="form-control form-control-solid batch-quantity" name="variation_0_batch_quantity[]" required placeholder="Enter Quantity">
                                                            </div>
                                                            <div class="col-md-6 mt-3">
                                                                <label class="form-label">Purchase Date</label>
                                                                <input type="date" class="form-control form-control-solid" name="variation_0_batch_date[]" required placeholder="Enter Purchase Date">
                                                            </div>
                                                            <div class="col-md-12 mt-3">
                                                                <label class="form-label">Receipt (Optional)</label>
                                                                <input type="file" class="form-control form-control-solid" name="variation_0_batch_receipt[]">
                                                            </div>
                                                            <div class="col-md-12 mt-3 d-flex align-items-end">
                                                                <button type="button" class="btn btn-danger remove-batch" disabled><i style="font-size: 16px; padding: 0;" class="fa-regular fa-trash-can"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-12">
                                                            <button type="button" class="btn btn-primary add-variation-batch" data-variation-index="0">Add Batch</button>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-4">
                                                            <div class="stock-summary">
                                                                <h5>Stock Summary</h5>
                                                                <p>Total Stock: <span id="variation_0_total_stock">0</span></p>
                                                                <p>Average Price: $<span id="variation_0_avg_price">0.00</span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 mt-3">
                                                <button type="button" class="btn btn-danger remove_variation float-end">Remove Variation</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <button type="button" class="btn btn-primary mt-3" id="add_variation">Add Variation</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary submitBtn" id="btn-template" onclick="add_product()">ADD PRODUCT</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
            $('.js-example-basic-single1').select2();

            // Initialize with first simple product batch

        });

        function get_sub_category(selectedValue) {
            $.ajax({
                url: "{{Route('get_sub_category_for_products')}}",
                type: "get",
                data: {
                    category_id: selectedValue,
                },
                cache: false,
                success: function (dataResult) {
                    $('#sub_category').html(dataResult.options);
                }
            });
        }

        function get_product_type(selectedValue) {
            if(selectedValue === 'simple') {
                $('#simple_product').show();
                $('#simple_product_batches').show();
                $('#variable_product').hide();
                $('#variable_product_batches').hide();
            } else {
                $('#simple_product').hide();
                $('#simple_product_batches').hide();
                $('#variable_product').show();
                $('#variable_product_batches').show();
            }
        }

        // Generate batch name for simple product
        function generateSimpleBatchName(index) {
            const productName = $('#name').val() || 'Product';
            const firstWord = productName.split(' ')[0].substring(0, 3).toUpperCase();
            const batchName = `${firstWord}-BATCH-${index+1}`;

            $(`#simple_batches_container .batch-item:eq(${index}) .batch-name`).val(batchName);
        }

        // Generate batch name for variation
        function generateVariationBatchName(variationIndex, batchIndex) {
            const productName = $('#name').val() || 'Product';
            const firstWord = productName.split(' ')[0].substring(0, 3).toUpperCase();
            const batchName = `${firstWord}-VAR${variationIndex+1}-BATCH-${batchIndex+1}`;

            $(`#variation_${variationIndex}_batches_container .batch-item:eq(${batchIndex}) .batch-name`).val(batchName);
        }

        // Calculate stock summary
        function calculateStockSummary(containerId) {
            let totalStock = 0;
            let totalValue = 0;

            $(`#${containerId} .batch-item`).each(function(index) {
                const quantity = parseFloat($(this).find('.batch-quantity').val()) || 0;
                const price = parseFloat($(this).find('.batch-price').val()) || 0;

                totalStock += quantity;
                totalValue += quantity * price;

                // Enable remove button if there are multiple batches
                if ($(`#${containerId} .batch-item`).length > 1) {
                    $(this).find('.remove-batch').prop('disabled', false);
                } else {
                    $(this).find('.remove-batch').prop('disabled', true);
                }
            });

            const avgPrice = totalStock > 0 ? (totalValue / totalStock).toFixed(2) : 0;

            // Update summary display
            if (containerId.includes('variation')) {
                const variationIndex = containerId.match(/\d+/)[0];
                $(`#variation_${variationIndex}_total_stock`).text(totalStock);
                $(`#variation_${variationIndex}_avg_price`).text(avgPrice);
            } else {
                $('#simple_total_stock').text(totalStock);
                $('#simple_avg_price').text(avgPrice);
            }
        }

        // Add batch for simple product
        $('#add_simple_batch').click(function() {
            const batchIndex = $('#simple_batches_container .batch-item').length;
            const newBatch = $(`
                <div class="row batch-item mb-3">
                    <div class="col-md-12">
                         <label class="form-label">Batch Name</label>
                        <input type="text" class="form-control form-control-solid batch-name" name="simple_batch_name[]" readonly>
                    </div>
                       <div class="col-md-6 mt-3">
                                                    <label class="form-label">Regular Price</label>
                                                    <input type="number" step="0.01" class="form-control form-control-solid w-100" name="reg_batch_price[]" id="reg_batch_price" placeholder="Enter Regular Price">
                                                </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Purchase Price</label>
                        <input type="number" step="0.01" class="form-control form-control-solid batch-price" name="simple_batch_price[]" required>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control form-control-solid batch-quantity" name="simple_batch_quantity[]" required>
                    </div>
                    <div class="col-md-6 mt-3">
                            <label class="form-label">Purchase Date</label>
                        <input type="date" class="form-control form-control-solid" name="simple_batch_date[]" required>
                    </div>
                    <div class="col-md-12 mt-3">
                           <label class="form-label">Receipt (Optional)</label>
                        <input type="file" class="form-control form-control-solid" name="simple_batch_receipt[]">
                    </div>
                    <div class="col-md-12 mt-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-batch"><i style="font-size: 16px; padding: 0;" class="fa-regular fa-trash-can"></i></button>
                    </div>
                </div>
            `);

            $('#simple_batches_container').append(newBatch);
            generateSimpleBatchName(batchIndex);
            calculateStockSummary('simple_batches_container');
            $('#name').on('input change', function() {
                generateSimpleBatchName(batchIndex);
        });
            // Auto-fill date with today's date
            $(`#simple_batches_container .batch-item:eq(${batchIndex}) input[type="date"]`).val(new Date().toISOString().slice(0, 10));
        });

        // Add variation
        $('#add_variation').click(function() {
            const variationIndex = $('.variation_item').length;

            const newVariation = $(`
                <div class="row mt-3 variation_item">
                    <div class="col-sm-12 mt-3">
                        <h4>Variation #${variationIndex+1}</h4>
                    </div>
                    <div class="col-sm-6 mt-3">
                          <label class="form-label">Product Variation</label>
                        <input type="text" class="form-control form-control-solid w-100" name="variation_product[]" placeholder="Enter Product Variation">
                    </div>
                    <div class="col-sm-6 mt-3">
                          <label class="form-label">Product Variation Value</label>
                        <input type="text" class="form-control form-control-solid w-100" name="variation_product_value[]" placeholder="Enter Product Variation Value">
                    </div>
                    <div class="col-sm-6 mt-3">
                         <label class="form-label">Variation Photo</label>
                        <input type="file" class="form-control form-control-solid w-100" name="variation_img[]">
                    </div>
                   <div class="col-sm-6 mt-3">
                                                    <label class="form-label">Product Variation SKU</label>
                                                    <input type="text" class="form-control form-control-solid w-100" name="variation_product_sku[]" placeholder="Enter Product Variation SKU">
                                                </div>

                    <div class="col-12 mt-4 variation-batches" id="variation_${variationIndex}_batches">
                        <div class="batch-section">
                            <div class="batch-header">
                                <h5>Stock Batches for this Variation</h5>
                            </div>

                            <div id="variation_${variationIndex}_batches_container">
                                <div class="row batch-item mb-3">
                                    <div class="col-md-12 mt-3">
                                         <label class="form-label">Batch Name</label>
                                        <input type="text" class="form-control form-control-solid batch-name" name="variation_${variationIndex}_batch_name[]" readonly>
                                    </div>
                                     <div class="col-md-6 mt-3">
                                      <label class="form-label">Regular Price</label>
                                       <input type="number" step="0.01" class="form-control form-control-solid w-100" name="variation_${variationIndex}_batch_reg_price[]" placeholder="Enter Regular Price">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                         <label class="form-label">Purchase Price</label>
                                        <input type="number" step="0.01" class="form-control form-control-solid batch-price" name="variation_${variationIndex}_batch_price[]" required placeholder="Enter Purchase Price">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                            <label class="form-label">Quantity</label>
                                        <input type="number" class="form-control form-control-solid batch-quantity" name="variation_${variationIndex}_batch_quantity[]" required placeholder="Enter Quantity">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                          <label class="form-label">Purchase Date</label>
                                        <input type="date" class="form-control form-control-solid" name="variation_${variationIndex}_batch_date[]" required placeholder="Enter Purchase Date">
                                    </div>
                                    <div class="col-md-12 mt-3">
                                         <label class="form-label">Receipt (Optional)</label>
                                        <input type="file" class="form-control form-control-solid" name="variation_${variationIndex}_batch_receipt[]">
                                    </div>
                                    <div class="col-md-12 mt-3 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger remove-batch" disabled><i style="font-size: 16px; padding: 0;" class="fa-regular fa-trash-can"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary add-variation-batch" data-variation-index="${variationIndex}">Add Batch</button>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="stock-summary">
                                        <h5>Stock Summary</h5>
                                        <p>Total Stock: <span id="variation_${variationIndex}_total_stock">0</span></p>
                                        <p>Average Price: $<span id="variation_${variationIndex}_avg_price">0.00</span></p>
                                    </div>
                                </div>
                            </div>

                        </div>
                              <div class="col-sm-12 mt-3">
                        <button type="button" class="btn btn-danger remove_variation float-end">Remove Variation</button>
                    </div>
                    </div>
                </div>
            `);

            $('#variable_product_container').append(newVariation);

            // Generate name for first batch in this variation
            generateVariationBatchName(variationIndex, 0);



            // Auto-fill date with today's date
            $(`#variation_${variationIndex}_batches_container .batch-item:eq(0) input[type="date"]`).val(new Date().toISOString().slice(0, 10));
        });

        // Add batch for variation
        $(document).on('click', '.add-variation-batch', function() {
            const variationIndex = $(this).data('variation-index');
            const batchIndex = $(`#variation_${variationIndex}_batches_container .batch-item`).length;

            const newBatch = $(`
                <div class="row batch-item mb-3">
                    <div class="col-md-12 mt-3">
                           <label class="form-label">Batch Name</label>
                        <input type="text" class="form-control form-control-solid batch-name" name="variation_${variationIndex}_batch_name[]" readonly>
                    </div>
                         <div class="col-md-6 mt-3">
                                      <label class="form-label">Regular Price</label>
                                       <input type="number" step="0.01" class="form-control form-control-solid w-100" name="variation_${variationIndex}_batch_reg_price[]" placeholder="Enter Regular Price">
                                    </div>
                    <div class="col-md-6 mt-3">
                         <label class="form-label">Purchase Price</label>
                        <input type="number" step="0.01" class="form-control form-control-solid batch-price" name="variation_${variationIndex}_batch_price[]" required placeholder="Enter Purchase Price">
                    </div>
                    <div class="col-md-6 mt-3">
                            <label class="form-label">Quantity</label>
                        <input type="number" class="form-control form-control-solid batch-quantity" name="variation_${variationIndex}_batch_quantity[]" required placeholder="Enter Quantity">
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Purchase Date</label>
                        <input type="date" class="form-control form-control-solid" name="variation_${variationIndex}_batch_date[]" required placeholder="Enter Purchase Date">
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="form-label">Receipt (Optional)</label>
                        <input type="file" class="form-control form-control-solid" name="variation_${variationIndex}_batch_receipt[]">
                    </div>
                    <div class="col-md-12 mt-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-batch"><i style="font-size: 16px; padding: 0;" class="fa-regular fa-trash-can"></i></button>
                    </div>
                </div>
            `);

            $(`#variation_${variationIndex}_batches_container`).append(newBatch);
            generateVariationBatchName(variationIndex, batchIndex);
            calculateStockSummary(`variation_${variationIndex}_batches_container`);
            $('#name').on('input change', function()  {
                generateVariationBatchName(variationIndex, batchIndex);
        });
            // Auto-fill date with today's date
            $(`#variation_${variationIndex}_batches_container .batch-item:eq(${batchIndex}) input[type="date"]`).val(new Date().toISOString().slice(0, 10));
        });

        // Remove batch
        $(document).on('click', '.remove-batch', function() {
            if ($(this).closest('[id$="_container"]').find('.batch-item').length > 1) {
                $(this).closest('.batch-item').remove();
                const containerId = $(this).closest('[id$="_container"]').attr('id');
                calculateStockSummary(containerId);
            } else {
                toastr.error('At least one batch is required');
            }
        });

        // Remove variation
        $(document).on('click', '.remove_variation', function() {
            if ($('.variation_item').length > 1) {
                $(this).closest('.variation_item').remove();

                // Renumber remaining variations
                $('.variation_item').each(function(index) {
                    $(this).find('h4').text(`Variation #${index+1}`);
                    const newIndex = index;
                    const oldIndex = $(this).index();

                    // Update all names and IDs
                    $(this).find('[id^="variation_"]').each(function() {
                        const oldId = $(this).attr('id');
                        const newId = oldId.replace(/variation_\d+/, `variation_${newIndex}`);
                        $(this).attr('id', newId);
                    });

                    $(this).find('[name^="variation_"]').each(function() {
                        const oldName = $(this).attr('name');
                        const newName = oldName.replace(/variation_\d+/, `variation_${newIndex}`);
                        $(this).attr('name', newName);
                    });

                    // Update data-variation-index on add batch buttons
                    $(this).find('.add-variation-batch').data('variation-index', newIndex);
                });
            } else {
                toastr.error('At least one variation is required');
            }
        });

        // Calculate stock when batch values change
        $(document).on('input', '.batch-quantity, .batch-price', function() {
            const containerId = $(this).closest('[id$="_container"]').attr('id');
            calculateStockSummary(containerId);
        });
        $('#name').on('input change', function()
        {
            generateVariationBatchName(0, 0);
            generateSimpleBatchName(0);
        });


        // Form validation

        function validateInputs() {
            let isValid = true;
            const requiredInputs = document.querySelectorAll('.required-input');
            console.log('Validating inputs:', requiredInputs);

            requiredInputs.forEach(input => {
                if (input.type === 'radio' || input.type === 'checkbox') {
                    const name = input.name;
                    const group = document.querySelectorAll('input[name="' + name + '"]');
                    const checked = document.querySelectorAll('input[name="' + name + '"]:checked').length > 0;
                    group.forEach(option => {
                        if (!checked) {
                            option.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            option.classList.remove('is-invalid');
                        }
                    });
                } else if (input.tagName.toLowerCase() === 'select') {
                    if (!input.value) {
                        input.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                } else if (input.tagName.toLowerCase() === 'textarea') {
                    if (!input.value.trim()) {
                        input.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                } else if ($('#product_type').val() === 'simple' && $('#simple_batches_container .batch-item').length === 0) {
                toastr.error('At least one stock batch is required for simple products');
                isValid = false;
            }
            else {
                    if (!input.value) {
                        input.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                }

            });

            return isValid;
        }
        // Submit form
        document.addEventListener('DOMContentLoaded', function () {
            FilePond.registerPlugin(
                FilePondPluginFileValidateSize,
                FilePondPluginFileValidateType,
                FilePondPluginImagePreview,
            );
            var pond = FilePond.create(document.getElementById('createInput'), {
                allowMultiple: true,
                maxFiles: 25,
                labelIdle: 'Drag & Drop your image or <span class="filepond--label-action">Browse</span>',
                maxFileSize: '500MB',

            });
        window.add_product = function() {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            if (validateInputs()) {
                const formData = new FormData(document.getElementById("add_product"));
                const pondFile = pond.getFiles();

                pondFile.forEach(files => {
                    formData.append('files[]', files.file);
                });

                const submitButton = $('#btn-template');
                submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

                $.ajax({
                    url: '{{ route("add_product_store") }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        submitButton.prop('disabled', false).html('ADD PRODUCT');

                        if (response.status === 'success') {
                            toastr.success(response.message);
                            setTimeout(function() {
                                window.location.href = '{{ route("all_products") }}';
                            }, 2000);
                        } else {
                            toastr.error(response.message || 'Something went wrong. Please try again.');
                        }
                    },
                    error: function(xhr) {
                        submitButton.prop('disabled', false).html('ADD PRODUCT');
                        toastr.error(xhr.responseJSON.message || 'Something went wrong. Please try again.');
                    }
                });
            }
        };
    });
    document.addEventListener("DOMContentLoaded", function () {
            let productNameInput = document.getElementById("name");
            let categorySelect = $("#category");
            let subCategorySelect = $("#sub_category");
            let skuField = document.getElementById("product_sku");

            function generateSKU() {
                let productName = productNameInput.value.trim();
                let category = categorySelect.find(":selected").text().trim();
                let subCategory = subCategorySelect.find(":selected").text().trim();

                if (productName && category && subCategory) {
                    let namePart = productName.substring(0, 3).toUpperCase();
                    let categoryPart = category.substring(0, 3).toUpperCase();
                    let subCategoryPart = subCategory.substring(0, 3).toUpperCase();
                    let randomNumber = Math.floor(1000 + Math.random() * 9000);
                    let sku = `${namePart}-${categoryPart}-${subCategoryPart}-${randomNumber}`;

                    console.log("Generated SKU:", sku);
                    skuField.value = sku;
                }
            }

            function generateVariationSKU(variationItem) {
                console.log("Generating variation SKU for item:", variationItem);
                let productName = productNameInput.value.trim();
                let category = categorySelect.find(":selected").text().trim();
                let subCategory = subCategorySelect.find(":selected").text().trim();
                let variationInput = variationItem.querySelector("input[name='variation_product[]']");
                let variationValueInput = variationItem.querySelector("input[name='variation_product_value[]']");
                let variationSKUField = variationItem.querySelector("input[name='variation_product_sku[]']");

                if (productName && category && subCategory && variationInput && variationValueInput) {
                    let namePart = productName.substring(0, 3).toUpperCase();
                    let categoryPart = category.substring(0, 3).toUpperCase();
                    let subCategoryPart = subCategory.substring(0, 3).toUpperCase();
                    let variationPart = variationInput.value.trim().substring(0, 3).toUpperCase();
                    let variationValuePart = variationValueInput.value.trim().substring(0, 3).toUpperCase();
                    let randomNumber = Math.floor(1000 + Math.random() * 9000);
                    let variationSKU = `${namePart}-${categoryPart}-${subCategoryPart}-${variationPart}-${variationValuePart}-${randomNumber}`;

                    console.log("Generated Variation SKU:", variationSKU);
                    variationSKUField.value = variationSKU;
                }
            }

            // Call SKU generation for main product
            if (productNameInput) {
                productNameInput.addEventListener("input", generateSKU);
            }
            categorySelect.on("select2:select", generateSKU);
            subCategorySelect.on("select2:select", generateSKU);

            // SKU generation for variations
            document.addEventListener("input", function (event) {
                if (event.target.matches("input[name='variation_product[]'], input[name='variation_product_value[]']")) {
                    let variationItem = event.target.closest(".variation_item");
                    generateVariationSKU(variationItem);
                }
            });

            // SKU generation when adding new variation
            document.getElementById("add_variation").addEventListener("click", function () {
                setTimeout(function () {
                    let newVariationItem = document.querySelector("#variable_product_container .variation_item:last-child");
                    generateVariationSKU(newVariationItem);
                }, 100);
            });

        });
    </script>

    <script>
        CKEDITOR.replace('product_short_des', {
            filebrowserUploadMethod: 'form'
        });
        CKEDITOR.replace('product_des', {
            filebrowserUploadMethod: 'form'
        });
    </script>
@endpush