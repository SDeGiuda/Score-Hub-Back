<?php

declare(strict_types=1);

namespace Src\Users\Domain\Actions;

use Src\Users\App\Notifications\UserRegisteredNotification;
use Src\Users\Domain\DataTransferObjects\UserDto;
use Src\Users\Domain\Models\User;

class StoreUserAction
{
    public function execute(UserDto $userDto): User
    {
        $user = new User();

        $user->name = $userDto->name;
        $user->email = $userDto->emailAddress;
        $user->password = $userDto->password;

        $user->save();

        $user->notify(new UserRegisteredNotification());

        return $user;
    }
}
