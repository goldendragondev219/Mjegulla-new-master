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
        Schema::create('subscription_packages', function (Blueprint $table) {
            $table->id()->index();
            $table->string('shop_id')->index();
            $table->string('package_type');
            $table->string('details');
            $table->string('amount')->index();
            $table->string('ends_at')->index();
            $table->enum('will_cancel', ['yes', 'no']);
            $table->string('sub_id')->index();
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
        Schema::dropIfExists('subscription_packages');
    }
};
