<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SystemUserSeeder extends Seeder
{
    public function run(): void
    {
        // Insert system user with ID 0 for classic games
        DB::table('users')->insert([
            'id' => 0,
            'name' => 'System',
            'username' => 'system',
            'email' => 'system@scorehub.app',
            'password' => Hash::make('system-reserved-user-do-not-use'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Reset the sequence to start from 1 for regular users
        DB::statement("SELECT setval(pg_get_serial_sequence('users', 'id'), (SELECT MAX(id) FROM users))");
    }
}
