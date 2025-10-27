<?php

declare(strict_types=1);

namespace Src\Users\Domain\Actions;

use Illuminate\Support\Facades\Hash;
use Src\Users\App\Notifications\UserRegisteredNotification;
use Src\Users\Domain\DataTransferObjects\UserDto;
use Src\Users\Domain\Models\User;

class SignUpAction
{
    public function execute(UserDto $userDto): User
    {
        $user = new User();

        $user->name = $userDto->name;
        $user->username = $userDto->username;
        $user->email = $userDto->emailAddress;
        $user->password = Hash::make($userDto->password);

        $user->save();

        $user->notify(new UserRegisteredNotification());

        return $user;
    }
}
