<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User de prueba para entrar rápido
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        // 10 Usuarios aleatorios
        User::factory(10)->create();

        // 20 Contactos aleatorios
        Contact::factory(20)->create();
    }
}
