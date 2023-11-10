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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('cj_status', ['CREATED', 'IN_CART', 'UNPAID', 'UNSHIPPED', 'SHIPPED', 'DELIVERED', 'CANCELLED'])->default('CREATED')->index();
            $table->string('cj_postal')->nullable();
            $table->string('cj_tracking')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('cj_status');
            $table->dropColumn('cj_postal');
            $table->dropColumn('cj_tracking');
        });
    }
};
