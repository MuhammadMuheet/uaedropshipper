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
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('variation_name');
            $table->string('variation_value');
            $table->string('variation_sku')->unique();
            $table->integer('variation_stock')->default(0);
            $table->decimal('variation_reg_price', 10, 2)->nullable();
            $table->decimal('variation_sale_price', 10, 2)->nullable();
            $table->string('variation_image')->nullable();
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
        Schema::dropIfExists('product_variations');
    }
};
