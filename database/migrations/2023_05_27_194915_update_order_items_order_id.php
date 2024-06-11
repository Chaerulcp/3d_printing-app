<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Schema\AbstractSchemaManager; // Import this class

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Get the Doctrine Schema Manager to access the foreign keys
            $schemaManager = $table->getConnection()->getDoctrineSchemaManager();
            $foreignKeys = $schemaManager->listTableForeignKeys('order_items');

            // Find the foreign key referencing `orders(id)` and drop it
            foreach ($foreignKeys as $foreignKey) {
                if ($foreignKey->getForeignTableName() == 'orders' && $foreignKey->getLocalColumns() == ['order_id']) {
                    $table->dropForeign($foreignKey->getName());
                    break; // Stop the loop after dropping the foreign key
                }
            }
            
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete(); 
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Get the Doctrine Schema Manager to access the foreign keys
            $schemaManager = $table->getConnection()->getDoctrineSchemaManager();
            $foreignKeys = $schemaManager->listTableForeignKeys('order_items');

            // Find the foreign key referencing `orders(id)` and drop it
            foreach ($foreignKeys as $foreignKey) {
                if ($foreignKey->getForeignTableName() == 'orders' && $foreignKey->getLocalColumns() == ['order_id']) {
                    $table->dropForeign($foreignKey->getName());
                    break; // Stop the loop after dropping the foreign key
                }
            }

            $table->foreign('order_id')->references('id')->on('orders'); 
        });
    }
};
