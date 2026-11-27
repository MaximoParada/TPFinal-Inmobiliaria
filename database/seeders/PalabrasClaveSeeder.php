<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PalabrasClaveSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiamos la tabla antes de insertar para evitar duplicados al re-ejecutar el seeder
        DB::table('palabras_clave')->truncate();

        $respuestas = DB::table('respuestas_bot')->pluck('id', 'clave');

        $palabras = [
            // Para alquilar
            ['palabra' => 'alquilar', 'clave' => 'alquiler_pasos'],
            ['palabra' => 'alquiler', 'clave' => 'alquiler_pasos'],

            // Requisitos alquiler
            ['palabra' => 'requisitos alquiler', 'clave' => 'alquiler_requisitos'],
            ['palabra' => 'garantia', 'clave' => 'alquiler_requisitos'],
            ['palabra' => 'recibo de sueldo', 'clave' => 'alquiler_requisitos'],

            // Pasos para comprar
            ['palabra' => 'comprar', 'clave' => 'compra_pasos'],
            ['palabra' => 'compra', 'clave' => 'compra_pasos'],

            // Requisitos comprar
            ['palabra' => 'requisitos comprar', 'clave' => 'compra_requisitos'],
            ['palabra' => 'escritura', 'clave' => 'compra_requisitos'],
            ['palabra' => 'credito', 'clave' => 'compra_requisitos'],

            // Contacto y horarios
            ['palabra' => 'telefono', 'clave' => 'contacto_info'],
            ['palabra' => 'celular', 'clave' => 'contacto_info'],
            ['palabra' => 'whatsapp', 'clave' => 'contacto_info'],
            ['palabra' => 'horario', 'clave' => 'contacto_info'],
            ['palabra' => 'horarios', 'clave' => 'contacto_info'],
            ['palabra' => 'contacto', 'clave' => 'contacto_info'],
            ['palabra' => 'direccion', 'clave' => 'contacto_info'],
            ['palabra' => 'ubicacion', 'clave' => 'contacto_info'],
            ['palabra' => 'mail', 'clave' => 'contacto_info'],
            ['palabra' => 'correo', 'clave' => 'contacto_info'],

        ];

        $filas = [];
        foreach ($palabras as $item) {
            if (isset($respuestas[$item['clave']])) {
                $filas[] = [
                    'palabra' => $item['palabra'],
                    'respuesta_bot_id' => $respuestas[$item['clave']],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('palabras_clave')->insert($filas);
    }
}