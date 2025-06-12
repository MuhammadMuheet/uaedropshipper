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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('role_id')->index();
            $table->text('users')->nullable();
            $table->text('user_role')->nullable();
            $table->text('user_logs')->nullable();
            $table->text('sellers')->nullable();
            $table->text('logistic_companies')->nullable();
            $table->text('categories')->nullable();
            $table->text('sub_categories')->nullable();
            $table->text('products')->nullable();
            $table->text('locations')->nullable();
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
        Schema::dropIfExists('permissions');
    }
};
