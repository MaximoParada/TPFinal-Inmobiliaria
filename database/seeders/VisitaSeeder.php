<?php

namespace Database\Seeders;

use App\Models\Propiedad;
use App\Models\User;
use App\Models\Visita;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VisitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuario = User::where('email', 'usuario@loncoprop.com')->first();
        $propiedad = Propiedad::first();

        if ($usuario && $propiedad) {
            Visita::create([
                'user_id' => $usuario->id,
                'propiedad_id' => $propiedad->id,
                'fecha' => now()->addDays(2)->toDateString(),
                'hora' => '10:30:00',
                'estado' => 'pendiente',
            ]);
        }
    }
}
