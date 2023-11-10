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
        Schema::create('store_impressions', function (Blueprint $table) {
            $table->id()->index();
            $table->string('store_id')->index();
            $table->string('user_location')->nullable()->index();
            $table->string('url')->nullable();
            $table->string('user_ip')->nullable();
            $table->string('referred')->nullable();
            $table->string('user_agent')->nullable();
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
        Schema::dropIfExists('store_impressions');
    }
};
