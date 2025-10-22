<?php

declare(strict_types=1);

namespace Tests\RequestFactories;

use Src\MatchResults\Domain\Enums\ResultStatusEnum;
use Worksome\RequestFactories\RequestFactory;

class StoreResultsRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'match_id' => 1,
            'results' => [
                [
                    'user_id' => 1,
                    'position' => 1,
                    'status' => fake()->randomElement(ResultStatusEnum::cases())->value,
                ],
            ],
        ];
    }
}
