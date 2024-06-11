<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Check if the foreign key exists before attempting to drop it
            if (Schema::hasColumn('order_items', 'order_id')) {
                $table->dropForeign('order_items_order_id_foreign'); 
            }

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Check if the foreign key exists before attempting to drop it
            if (Schema::hasColumn('order_items', 'order_id')) {
                $table->dropForeign('order_items_order_id_foreign'); 
            }
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }
};
