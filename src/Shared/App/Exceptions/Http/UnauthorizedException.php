<?php

declare(strict_types=1);

namespace Src\Shared\App\Exceptions\Http;

class UnauthorizedException extends HttpException
{
    /**
     * An HTTP status code.
     */
    protected int $status = 401;

    /**
     * An error code.
     */
    protected string $errorCode = 'unauthorized';
}
