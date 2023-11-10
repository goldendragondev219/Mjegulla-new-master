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
        Schema::table('products', function (Blueprint $table) {
            $table->enum('deleted', ['yes', 'no'])->default('no')->index();
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->enum('deleted', ['yes', 'no'])->default('no')->index();
        });

        Schema::table('variants', function (Blueprint $table) {
            $table->enum('deleted', ['yes', 'no'])->default('no')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('deleted');
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn('deleted');
        });

        Schema::table('variants', function (Blueprint $table) {
            $table->dropColumn('deleted');
        });
    }
};
