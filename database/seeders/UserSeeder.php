<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@loncoprop.com'],
            [
                'name' => 'Administrador LoncoProp',
                'telefono' => '2994000001',
                'rol' => 'admin',
                'password' => Hash::make('Admin1234!'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'usuario@loncoprop.com'],
            [
                'name' => 'Agustina Vega',
                'telefono' => '2994000002',
                'rol' => 'usuario',
                'password' => Hash::make('Usuario1234!'),
            ]
        );
    }
}
