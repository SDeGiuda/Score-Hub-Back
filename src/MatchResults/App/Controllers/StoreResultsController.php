<?php

declare(strict_types=1);

namespace Src\MatchResults\App\Controllers;

use Src\MatchResults\App\Requests\StoreResultsRequest;

class StoreResultsController
{
    public function __invoke(StoreResultsRequest $request): void
    {
        $request->toDtos();
    }
}
