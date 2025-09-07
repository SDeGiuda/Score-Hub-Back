<?php

declare(strict_types=1);

namespace Src\Users\App\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Src\Shared\App\Exceptions\Http\UnauthorizedException;
use Src\Users\Domain\Actions\LogoutAction;

class LogoutController
{
    public function __invoke(Request $request, LogoutAction $logoutAction): Response
    {
        $user = $request->user();

        if (! $user) {
            throw new UnauthorizedException();
        }

        $logoutAction->execute();

        return response()->noContent();
    }
}
