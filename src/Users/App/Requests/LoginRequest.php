<?php

declare(strict_types=1);

namespace Src\Users\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public const string EMAIL = 'email';

    public const string PASSWORD = 'password';

    public function rules(): array
    {
        return [
            self::EMAIL => ['required', 'email:strict'],
            self::PASSWORD => ['required'],
        ];
    }
}
