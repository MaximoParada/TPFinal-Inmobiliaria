<?php

namespace Database\Seeders;

use App\Models\Caracteristica;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CaracteristicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $caracteristicas = [
            'Patio',
            'Cochera',
            'Parrilla',
            'Apto crédito',
            'Losa radiante',
            'Balcón',
        ];

        foreach ($caracteristicas as $nombre) {
            Caracteristica::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
