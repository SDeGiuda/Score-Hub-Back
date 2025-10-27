<?php

declare(strict_types=1);

namespace Src\Games\App\Controllers;

use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Src\Games\App\Resources\GameResource;
use Src\Games\Domain\Models\Game;

class ListGameController
{
    public function __invoke(): JsonResponse
    {
        $filteredGames = QueryBuilder::for(Game::query())
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::callback('classic', function (QueryBuilder $query, $value) {
                    if ($value === 'true' || $value === true || $value === '1') {
                        return $query->whereNull('user_id');
                    }

                    return $query->whereNotNull('user_id');
                }),
            ])
        ->get();

        return GameResource::collection($filteredGames)->response();
    }
}
