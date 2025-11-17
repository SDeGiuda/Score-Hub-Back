<?php

declare(strict_types=1);

namespace Tests\RequestFactories;

use Worksome\RequestFactories\RequestFactory;

class CreateGameRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'number_of_players' => fake()->numberBetween(2, 10),
            'turn_duration' => fake()->numberBetween(30, 120),
            'round_duration' => fake()->numberBetween(5, 30),
            'rounds' => fake()->numberBetween(1, 10),
            'has_turns' => fake()->boolean(),
            'has_teams' => fake()->boolean(),
            'min_team_length' => 1,
            'max_team_length' => 5,
            'rules' => fake()->paragraph(),
            'starting_points' => 0,
            'finishing_points' => fake()->numberBetween(10, 100),
            'is_winning' => true,
            'icon' => fake()->randomElement(['CardsThree', 'DiceFive', 'Trophy', 'Football', 'Target']),
            'color' => fake()->randomElement(['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6']),
            'bg_color' => fake()->randomElement(['#dbeafe', '#d1fae5', '#fef3c7', '#fee2e2', '#ede9fe']),
            'description' => fake()->sentence(),
        ];
    }
}