<?php

declare(strict_types=1);

namespace Src\Users\Domain\Actions;

use Illuminate\Contracts\Auth\Factory as AuthFactory;
use PHPOpenSourceSaver\JWTAuth\Factory as JWTAuth;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;
use Src\Shared\App\Exceptions\Http\UnauthorizedException;
use Src\Users\Domain\DataTransferObjects\LoginDto;
use Src\Users\Domain\Models\User;

final class LoginAction
{
    public function __construct(
        private readonly AuthFactory $factory,
        private readonly JWTAuth $jwtAuth,
    ) {
    }

    /** @throws UnauthorizedException */
    public function execute(array $credentials): LoginDto
    {
        /** @var JWTGuard $guard */
        $guard = $this->factory->guard('api');

        if (! $guard->attempt($credentials)) {
            throw new UnauthorizedException();
        }

        /** @var User $user */
        $user = $guard->user();


        /** @var string $token */
        $token = $guard->tokenById($user->getKey());

        return new LoginDto(
            $token,
            'Bearer',
            $this->jwtAuth->getTTL() * 60,
        );
    }
}
