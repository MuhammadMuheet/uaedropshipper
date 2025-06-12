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
        Schema::create('seller_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('role_id')->index();
            $table->text('sub_sellers')->nullable();
            $table->text('seller_role')->nullable();
            $table->text('seller_logs')->nullable();
            $table->text('cart')->nullable();
            $table->text('checkout')->nullable();
            $table->text('orders')->nullable();
            $table->text('products')->nullable();
            $table->text('settings')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seller_permissions');
    }
};
