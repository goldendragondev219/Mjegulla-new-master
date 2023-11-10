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
        Schema::table('admin_menu_items', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
            $table->foreignId('shop_id')->references('id')->on('shops')->onDelete('cascade');
            $table->index('shop_id');
        });
        Schema::table('admin_menus', function (Blueprint $table) {
            $table->index('user_id');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('shop_id');
            $table->foreignId('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_menu_items', function (Blueprint $table) {
            $table->dropForeign('lists_user_id_foreign');
            $table->dropIndex('lists_user_id_index');
            $table->dropColumn('user_id');
            $table->dropForeign('lists_shop_id_foreign');
            $table->dropIndex('lists_shop_id_index');
            $table->dropColumn('shop_id');
        });
        Schema::table('admin_menus', function (Blueprint $table) {
            $table->dropForeign('lists_user_id_foreign');
            $table->dropIndex('lists_user_id_index');
            $table->dropColumn('user_id');
            $table->dropForeign('lists_shop_id_foreign');
            $table->dropIndex('lists_shop_id_index');
            $table->dropColumn('shop_id');
        });
    }
};
