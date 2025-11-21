<?php

declare(strict_types=1);

namespace Src\Users\Domain\Actions;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;
use Src\Users\Domain\Models\User;

class ListUserAction
{
    /**
     * @return LengthAwarePaginator<int, User>
     */
    public function execute(): LengthAwarePaginator
    {
        /** @var QueryBuilder<User> $query */
        $query = QueryBuilder::for(User::class)
            ->allowedFilters(['email'])
            ->allowedSorts('email')
            ->orderBy('id', 'desc')
            ->where('id', '>', 0);

        return $query->paginate();
    }
}
