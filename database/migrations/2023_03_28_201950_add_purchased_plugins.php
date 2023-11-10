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
        Schema::create('purchased_plugins', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('user_id')->index();
            $table->string('shop_id')->index();
            $table->string('plugin_id')->index();
            $table->string('name')->index()->nullable();
            $table->string('image')->index()->nullable();
            $table->text('description')->nullable();
            $table->float('price')->default(0);
            $table->enum('enabled', ['yes', 'no']);
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
        Schema::dropIfExists('purchased_plugins');
    }
};
