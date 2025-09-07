<?php

declare(strict_types=1);

namespace Src\Users\Domain\Actions;

use Illuminate\Contracts\Auth\Factory as AuthFactory;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

readonly class LogoutAction
{
    public function __construct(
        private AuthFactory $factory,
    ) {
    }

    public function execute(): void
    {
        /** @var JWTGuard $guard */
        $guard = $this->factory->guard();
        $guard->logout();
    }
}
