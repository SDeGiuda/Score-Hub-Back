<?php

declare(strict_types=1);

namespace Src\Users\Domain\DataTransferObjects;

class ResetPasswordDto
{
    public function __construct(
        public string $token,
        public readonly string $email,
        public readonly string $password,
    ) {
    }
}
