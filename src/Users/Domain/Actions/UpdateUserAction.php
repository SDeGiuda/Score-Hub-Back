<?php

declare(strict_types=1);

namespace Src\Users\Domain\Actions;

use Src\Users\Domain\DataTransferObjects\UserDto;
use Src\Users\Domain\Models\User;

class UpdateUserAction
{
    public function execute(User $user, UserDto $userDto): User
    {
        $user->name = $userDto->name;
        $user->email = $userDto->emailAddress;
        $user->password = $userDto->password;

        $user->save();

        return $user;
    }
}
