<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create system user first (id = 0) for classic games
        $this->call(SystemUserSeeder::class);

        // Create classic games owned by system user
        $this->call(ClassicGamesSeeder::class);

        // Create regular users
        UserFactory::new()->createMany(35);
    }
}
