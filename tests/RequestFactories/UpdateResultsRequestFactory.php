<?php

declare(strict_types=1);

namespace Tests\RequestFactories;

use Database\Factories\GameMatchFactory;
use Database\Factories\UserFactory;
use Src\MatchResults\Domain\Enums\ResultStatusEnum;
use Src\MatchResults\Domain\Models\MatchResult;
use Worksome\RequestFactories\RequestFactory;

class UpdateResultsRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        $match = GameMatchFactory::new()->createOne();
        $users = UserFactory::new()->createMany(2);

        // Create match results for the users
        foreach ($users as $user) {
            MatchResult::create([
                'match_id' => $match->id,
                'user_id' => $user->id,
                'position' => 0,
                'status' => ResultStatusEnum::ACTIVE,
            ]);
        }

        return [
            'match_id' => $match->id,
            'results' => $users->map(fn ($user, $index) => [
                'user_id' => $user->id,
                'position' => $index + 1,
                'points' => fake()->numberBetween(0, 100),
            ])->toArray(),
        ];
    }
}