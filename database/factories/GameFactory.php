<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Games\Domain\EndingEnum;
use Src\Games\Domain\Models\Game;
use Src\Users\Domain\Models\User;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    protected $model = Game::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'number_of_players' => fake()->numberBetween(2, 10),
            'turn_duration' => fake()->numberBetween(30, 300),
            'round_duration' => fake()->numberBetween(5, 60),
            'rounds' => fake()->numberBetween(1, 10),
            'ending' => fake()->randomElement(EndingEnum::cases())->value,
            'has_turns' => fake()->boolean(),
            'has_teams' => fake()->boolean(),
            'min_team_length' => fake()->numberBetween(1, 3),
            'max_team_length' => fake()->numberBetween(4, 10),
            'rules' => fake()->paragraph(),
            'min_points' => fake()->numberBetween(0, 50),
            'max_points' => fake()->numberBetween(100, 500),
            'icon' => fake()->emoji(),
            'color' => fake()->hexColor(),
            'bg_color' => fake()->hexColor(),
            'description' => fake()->sentence(),
            'user_id' => User::factory(),
        ];
    }
}
