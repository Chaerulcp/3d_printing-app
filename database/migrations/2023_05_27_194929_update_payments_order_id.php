<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Hapus foreign key yang ada jika ada
            if (Schema::hasTable('payments')) { // Check if the table 'payments' exists
                $columns = Schema::getColumnListing('payments'); // Get all column names
                if (in_array('order_id', $columns)) { // Check if column 'order_id' exists
                    $foreignKey = collect(Schema::getConnection()->getDoctrineSchemaManager()->listTableForeignKeys('payments'))
                        ->first(function ($fk) {
                            return $fk->getColumns() === ['order_id']; 
                        });

                    if ($foreignKey !== null) {
                        $table->dropForeign($foreignKey->getName()); // Drop the foreign key
                    }
                }
            }
            
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete(); // Add a new foreign key with cascade on delete
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Drop the foreign key if it exists (same as in the 'up' method)
            if (Schema::hasTable('payments')) { // Check if the table 'payments' exists
                $columns = Schema::getColumnListing('payments'); // Get all column names
                if (in_array('order_id', $columns)) { // Check if column 'order_id' exists
                    $foreignKey = collect(Schema::getConnection()->getDoctrineSchemaManager()->listTableForeignKeys('payments'))
                        ->first(function ($fk) {
                            return $fk->getColumns() === ['order_id'];
                        });

                    if ($foreignKey !== null) {
                        $table->dropForeign($foreignKey->getName()); // Drop the foreign key
                    }
                }
            }
            $table->foreign('order_id')->references('id')->on('orders'); // Add a foreign key without cascade on delete
        });
    }
};
