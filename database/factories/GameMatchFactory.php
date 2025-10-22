<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Games\Domain\Models\Game;
use Src\Matches\Domain\Models\GameMatch;
use Src\Users\Domain\Models\User;

/**
 * @extends Factory<GameMatch>
 */
class GameMatchFactory extends Factory
{
    protected $model = GameMatch::class;

    public function definition(): array
    {
        return [
            'creator_id' => User::factory(),
            'game_id' => Game::factory(),
            'name' => fake()->words(3, true),
        ];
    }
}
