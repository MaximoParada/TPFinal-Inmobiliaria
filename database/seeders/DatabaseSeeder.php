<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PropiedadesSeeder::class);
        $this->call(ReferenciasTasacionSeeder::class);
        $this->call(RespuestasBotSeeder::class);
        $this->call(PalabrasClaveSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CaracteristicaSeeder::class);
        $this->call(VisitaSeeder::class);
    }
}
