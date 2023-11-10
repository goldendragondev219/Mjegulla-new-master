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
        Schema::create('shops', function (Blueprint $table) {
            $table->id()->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->text('shop_name');
            $table->string('shop_description')->nullable()->default(NULL);
            $table->string('shop_seo_keywords')->nullable()->default(NULL);
            $table->string('my_shop_url')->unique()->index();
            $table->string('shop_image_url')->nullable()->default(NULL);
            $table->string('home_featured_details', 500)->nullable()->default('off');
            $table->string('header_message')->nullable()->default(NULL);
            $table->string('social_networks')->nullable()->default('off');
            $table->string('stripe_session')->nullable()->default(NULL);
            $table->string('products_available')->default(2)->index();
            $table->string('total_products')->default(0)->index();
            $table->float('balance_available')->default(0)->index();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('shops');
    }
    
    
};
