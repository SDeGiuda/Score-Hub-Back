<?php

declare(strict_types=1);

namespace Src\Shared\App\Exceptions\Http;

class RelationNotFoundException extends HttpException
{
    /**
     * An HTTP status code.
     */
    protected int $status = 422;

    /**
     * An error code.
     */
    protected string $errorCode = 'relation_not_found';
}
