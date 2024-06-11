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
            // Hapus Foreign Key jika ada
            $schemaManager = Schema::getConnection()->getDoctrineSchemaManager();
            $foreignKeys = $schemaManager->listTableForeignKeys('order_items');

            foreach ($foreignKeys as $foreignKey) {
                if ($foreignKey->getForeignTableName() == 'orders' && $foreignKey->getLocalColumns()[0] == 'order_id') {
                    $table->dropForeign($foreignKey->getName());
                    break; // Hentikan perulangan setelah foreign key dihapus
                }
            }

            // Tambahkan foreign key baru dengan cascadeOnDelete
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete(); 
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Hapus foreign key dengan cascade on delete
            $schemaManager = Schema::getConnection()->getDoctrineSchemaManager();
            $foreignKeys = $schemaManager->listTableForeignKeys('order_items');

            foreach ($foreignKeys as $foreignKey) {
                if ($foreignKey->getForeignTableName() == 'orders' && $foreignKey->getLocalColumns()[0] == 'order_id') {
                    $table->dropForeign($foreignKey->getName());
                    break; // Hentikan perulangan setelah foreign key dihapus
                }
            }

            // Tambahkan kembali foreign key tanpa cascade on delete
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }
};
