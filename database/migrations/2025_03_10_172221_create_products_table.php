<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->index();
            $table->unsignedBigInteger('sub_category_id ')->index();
            $table->text('product_name')->nullable();
            $table->text('product_short_des')->nullable();
            $table->longText('product_des')->nullable();
            $table->text('product_image')->nullable();
            $table->longText('product_gallery')->nullable();
            $table->text('product_type')->nullable();
            $table->text('product_reg_price')->nullable();
            $table->text('product_sale_price')->nullable();
            $table->text('product_stock')->nullable();
            $table->enum('status', ['active', 'block'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
