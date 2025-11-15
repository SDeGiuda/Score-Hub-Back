<?php

namespace Database\Factories;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Games\Domain\Models\Game;
use Src\Matches\Domain\Models\GameMatch;
use Src\MatchResults\Domain\Models\MatchResult;
use Src\Users\Domain\Models\User;

/** @extends \Illuminate\Database\Eloquent\Factories\Factory<\Src\MatchResults\Domain\Models\MatchResult> */
class MatchResultsFactory extends Factory
{
    protected $model = MatchResult::class;

    public function definition(): array
    {
        return [
            'position' => $this->faker->randomNumber(),
            'status' => $this->faker->word(),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
            'points' => $this->faker->randomNumber(),

            'user_id' => UserFactory::new(),
            'match_id' => GameMatchFactory::new(),
        ];
    }

    /**
     * Asocia el resultado a un usuario específico.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn () => ['user_id' => $user->id]);
    }

    /**
     * Asocia el resultado a un match específico.
     */
    public function forMatch(GameMatch $match): static
    {
        return $this->state(fn () => ['match_id' => $match->id]);
    }

    public function forGame(Game $game): static
    {
        return $this->state(function () use ($game) {

            $match = GameMatchFactory::new()->forGame($game)->createOne();

            return ['match_id' => $match->id];
        });
    }
}
