<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Factories\GameFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Src\Users\Domain\Models\User;

class SystemUserSeeder extends Seeder
{
    public function run(): void
    {
        // Insert system user with ID 0 for classic games
        $user = new User(['id'=>0]);
        $user->name = 'admin';
        $user->username = 'admin';
        $user->password = 'password';
        $user->email = 'fake@email.com';
        $user->save();
    }
}
