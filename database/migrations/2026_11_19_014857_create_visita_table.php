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
        Schema::create('visita', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('propiedad_id')->constrained('propiedades')->cascadeOnDelete();
            $table->string('nombre_cliente')->nullable();
            $table->string('telefono_cliente')->nullable();
            $table->string('fecha_elegida')->nullable();

            // Campos estructurados para el Turnero y FullCalendar
            $table->date('fecha');
            $table->time('hora')->nullable();
            $table->string('estado')->default('pendiente'); // pendiente, confirmada, cancelada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visita');
    }
};
