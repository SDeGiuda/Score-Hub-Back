<?php

declare(strict_types=1);

namespace Tests\RequestFactories;

use Database\Factories\GameFactory;
use Database\Factories\UserFactory;
use Worksome\RequestFactories\RequestFactory;

class CreateMatchRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        $creator = UserFactory::new()->createOne();
        $game = GameFactory::new()->createOne();
        $players = UserFactory::new()->createMany(2);

        return [
            'name' => fake()->words(3, true),
            'creator_id' => $creator->id,
            'game_id' => $game->id,
            'players' => $players->pluck('username')->toArray(),
        ];
    }
}