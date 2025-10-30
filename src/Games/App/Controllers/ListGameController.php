<?php

declare(strict_types=1);

namespace Src\Games\App\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Src\Games\App\Resources\GameResource;
use Src\Games\Domain\Models\Game;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Container\Attributes\CurrentUser;

final readonly class ListGameController
{
    public function __invoke(Request $request, #[CurrentUser] $user): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);

        // Validate per_page is between 1 and 100
        $perPage = max(1, min(100, $perPage));

        $filteredGames = QueryBuilder::for(Game::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::callback('classic', function (Builder $query, $value) {
                    if ($value === 'true' || $value === true || $value === '1') {
                        return $query->whereNull('user_id');
                    }

                    return $query->whereNotNull('user_id');
                }),
                AllowedFilter::callback('me',function (Builder $query, $value) use ($user) {
                    if ($value === 'true' || $value === true || $value === '1') {
                        return $query->where('user_id',$user->id);
                    }

                    return $query;
                }),
            ])
            ->paginate($perPage)
            ->appends($request->query());

        return GameResource::collection($filteredGames)->response();
    }
}
