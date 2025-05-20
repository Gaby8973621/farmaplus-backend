<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  // database/migrations/xxxx_xx_xx_create_detalle_pedidos_table.php

public function up()
{
    Schema::create('detalle_pedidos', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('pedido_id');
        $table->unsignedBigInteger('producto_id');
        $table->integer('cantidad');
        $table->decimal('precio_unitario', 8, 2);
        $table->timestamps();

        $table->foreign('pedido_id')->references('id')->on('pedidos')->onDelete('cascade');
        $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
    });
}

};
