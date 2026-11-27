<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('referencia_tasaciones', function (Blueprint $table) {
            $table->id();
            $table->string('zona');
            $table->string('tipo_propiedad'); // departamento, casa, terreno, local
            $table->enum('operacion', ['venta', 'alquiler']);
            $table->decimal('valor_m2', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referencia_tasaciones');
    }
};
