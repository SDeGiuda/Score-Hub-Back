<?php

declare(strict_types=1);

namespace Database\Factories;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Games\Domain\Models\Game;

/** @extends Factory<\Src\Games\Domain\Models\Game> */
class GameFactory extends Factory
{
    protected $model = Game::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'number_of_players' => $this->faker->randomNumber(),
            'turn_duration' => $this->faker->randomNumber(),
            'round_duration' => $this->faker->randomNumber(),
            'has_turns' => $this->faker->boolean(),
            'has_teams' => $this->faker->boolean(),
            'rules' => $this->faker->word(),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
            'rounds' => $this->faker->randomNumber(),
            'min_team_length' => $this->faker->randomNumber(),
            'max_team_length' => $this->faker->randomNumber(),
            'user_id' => UserFactory::new(),
            'starting_points' => $this->faker->randomNumber(),
            'finishing_points' => $this->faker->randomNumber(),
            'description' => $this->faker->text(),
            'icon' => $this->faker->word(),
            'color' => $this->faker->word(),
            'bg_color' => $this->faker->word(),
            'is_winning' => $this->faker->boolean(),
        ];
    }
}
