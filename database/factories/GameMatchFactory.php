<?php

namespace Database\Factories;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Games\Domain\Models\Game;
use Src\Matches\Domain\Models\GameMatch;
use Src\Users\Domain\Models\User;

/** @extends Factory<GameMatch> */
class GameMatchFactory extends Factory
{
    protected $model = GameMatch::class;

    public function definition(): array
    {
        return [
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
            'name' => $this->faker->name(),

            'creator_id' => UserFactory::new(),
            'game_id' => GameFactory::new(),
        ];
    }
    public function forGame(Game $game): static
    {
        return $this->state(fn () => ['game_id' => $game->id]);
    }
}
