<?php

declare(strict_types=1);

namespace Src\Users\App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Src\Users\Domain\DataTransferObjects\ResetPasswordDto;

class ResetPasswordRequest extends FormRequest
{
    public const string TOKEN = 'token';

    public const string EMAIL = 'email';

    public const string PASSWORD = 'password';

    public function rules(): array
    {
        return [
            self::TOKEN => ['required'],
            self::EMAIL => ['required', 'email', 'exists:users,email'],
            self::PASSWORD => ['required', 'string', 'min:8', 'confirmed'],
            ];
    }

    public function toDto(): ResetPasswordDto
    {
        return new ResetPasswordDto(
            token: $this . $this->string(self::TOKEN)->toString(),
            email: $this->string(self::EMAIL)->toString(),
            password: $this->string(self::PASSWORD)->toString(),
        );
    }
}
