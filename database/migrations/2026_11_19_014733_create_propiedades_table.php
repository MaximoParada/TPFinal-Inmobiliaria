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
        Schema::create('propiedades', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->enum('operacion', ['alquiler', 'venta']);
            $table->string('direccion');
            $table->string('zona');
            $table->integer('ambientes');
            $table->integer('dormitorios');
            $table->integer('metros_cuadrados');
            $table->decimal('precio', 12, 2);
            $table->decimal('expensas', 10, 2)->default(0);
            $table->json('imagenes')->nullable(); // Guarda array de rutas para el carrusel
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propiedades');
    }
};
