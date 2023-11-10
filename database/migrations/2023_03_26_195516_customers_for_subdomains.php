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
        Schema::create('customers', function (Blueprint $table) {
            $table->id()->index();
            $table->string('name')->index();
            $table->string('email')->index();
            $table->string('shop_id')->index();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('customer_id')->index();
            $table->timestamps();
        
            $table->unique(['email', 'shop_id']);
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
