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
        Schema::create('orders', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('user_id')->index();
            $table->string('shop_id')->index();
            $table->string('product_id')->index();
            $table->string('variant_id')->index();
            $table->float('shipping_price');
            $table->float('amount');
            $table->string('billing_id')->index();
            $table->string('stripe_payment_id')->nullable();
            $table->enum('completed', ['yes', 'no'])->default('no')->index();
            $table->enum('shipped', ['yes', 'no'])->default('no')->index();
            $table->enum('delivered', ['yes', 'no'])->default('no')->index();
            $table->string('payment_method')->nullable()->index();
            $table->timestamp('created_at')->index();
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
        Schema::dropIfExists('orders');
    }
};
