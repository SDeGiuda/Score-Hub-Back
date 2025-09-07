<?php

declare(strict_types=1);

namespace Tests\Feature\Users;

use Database\Factories\UserFactory;
use Src\Users\App\Controllers\SignUpController;
use function Pest\Laravel\getJson;

describe('users', function (): void {
    /** @see SignUpController */
    it('can list users successfully', function (): void {
        $users = UserFactory::new()
            ->createMany(5);

        getJson(url('/api/users'))
            ->assertSuccessful()
            ->assertJsonCount(5, 'data');
    });
});
