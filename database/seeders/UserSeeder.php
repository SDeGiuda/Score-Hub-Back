<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Src\Users\Domain\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@scorehub.com',
            'password' => 'password',
        ]);

        // Create test users
        User::create([
            'name' => 'Juan Pérez',
            'username' => 'juanperez',
            'email' => 'juan@example.com',
            'password' => 'password',
        ]);

        User::create([
            'name' => 'María García',
            'username' => 'mariagarcia',
            'email' => 'maria@example.com',
            'password' => 'password',
        ]);

        User::create([
            'name' => 'Carlos López',
            'username' => 'carloslopez',
            'email' => 'carlos@example.com',
            'password' => 'password',
        ]);

        User::create([
            'name' => 'Ana Martínez',
            'username' => 'anamartinez',
            'email' => 'ana@example.com',
            'password' => 'password',
        ]);

        // Create random users
        UserFactory::new()->count(30)->create();
    }
}
