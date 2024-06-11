<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Remove the Foreign Key if it exists before creating a new one.
            if (Schema::hasTable('order_items')) {
                $foreignKeys = $table->getForeignKeyConstraints();
                foreach ($foreignKeys as $foreignKey) {
                    if ($foreignKey->getColumns()[0] == 'order_id'
                        && $foreignKey->getOn()[0] == 'orders'
                        && $foreignKey->getReferences()[0] == 'id') {

                        Schema::table('order_items', function (Blueprint $table) use ($foreignKey) {
                            $table->dropForeign($foreignKey->getName());
                        });
                    }
                }
            }
            
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete(); 
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasTable('order_items')) {
                $foreignKeys = $table->getForeignKeyConstraints();
                foreach ($foreignKeys as $foreignKey) {
                    if ($foreignKey->getColumns()[0] == 'order_id'
                        && $foreignKey->getOn()[0] == 'orders'
                        && $foreignKey->getReferences()[0] == 'id') {

                        Schema::table('order_items', function (Blueprint $table) use ($foreignKey) {
                            $table->dropForeign($foreignKey->getName());
                        });
                    }
                }
            }
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }
};

