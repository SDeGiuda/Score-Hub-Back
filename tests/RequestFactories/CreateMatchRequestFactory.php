<?php

declare(strict_types=1);

namespace Tests\RequestFactories;

use Worksome\RequestFactories\RequestFactory;

class CreateMatchRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'creator_id' => 1,
            'game_id' => 1,
            'players' => [fake()->userName(), fake()->userName()],
        ];
    }
}
