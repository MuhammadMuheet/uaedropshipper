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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id')->index();
            $table->string('name', 255);
            $table->text('image')->nullable();
            $table->string('email', 255)->unique();
            $table->text('store_name')->nullable();
            $table->text('average_orders')->nullable();
            $table->text('whatsapp')->nullable();
            $table->text('mobile')->nullable();
            $table->text('dropshipping_experience')->nullable();
            $table->text('dropshipping_status')->nullable();
            $table->text('bank')->nullable();
            $table->text('ac_title')->nullable();
            $table->text('ac_no')->nullable();
            $table->text('iban')->nullable();
            $table->enum('role', ['admin', 'user', 'sub_admin', 'seller'])->default('user');
            $table->text('type')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->enum('status', ['active', 'block', 'pending'])->default('pending');
            $table->string('remember_token', 100)->nullable();
            $table->string('shopify_domain')->nullable(); // e.g., store-name.myshopify.com
            $table->string('shopify_token')->nullable();  // Shopify access token (OAuth)
            $table->text('shopify_store_data')->nullable(); // optional: store JSON response
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};