<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferenciasTasacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('referencia_tasaciones')->insert([
            [
                'zona' => 'Zona Este',
                'tipo_propiedad' => 'departamento',
                'operacion' => 'venta',
                'valor_m2' => 1800000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'zona' => 'Centro',
                'tipo_propiedad' => 'departamento',
                'operacion' => 'alquiler',
                'valor_m2' => 13000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'zona' => 'Alta Barda',
                'tipo_propiedad' => 'casa',
                'operacion' => 'venta',
                'valor_m2' => 1550000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
