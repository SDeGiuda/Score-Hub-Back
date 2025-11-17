<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Factories\GameFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SystemUserSeeder extends Seeder
{
    public function run(): void
    {
        // Insert system user with ID 0 for classic games
        UserFactory::new()->createOne(['id' => 0]);
    }
}
