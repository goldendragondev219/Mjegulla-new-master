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
            $table->id()->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('shop_id')->index();
            $table->string('product_name')->index();
            $table->text('product_description')->nullable()->default(NULL);
            $table->text('variant_id');
            $table->string('product_sku')->nullable()->index();
            $table->string('quantity')->index();
            $table->float('base_price')->index();
            $table->float('base_price_discount')->nullable()->index();
            $table->string('product_seo_keywords')->nullable()->default(NULL);
            $table->string('product_url')->index();
            $table->unsignedBigInteger('product_category')->index();
            $table->string('shipping_rates')->nullable()->default(NULL);
            $table->integer('product_sales')->default(0);
            $table->string('product_single_image');
            $table->integer('product_views')->default(0);
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
