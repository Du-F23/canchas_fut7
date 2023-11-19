<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Roles;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Roles::create([
            'name' => 'Administrador'
        ]);

        Roles::create([
            'name' => 'Arbitro'
        ]);

        Roles::create([
            'name' => 'Capitan'
        ]);

        Roles::create([
            'name' => 'Jugador'
        ]);
    }
}
