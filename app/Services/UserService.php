<?php

namespace App\Services;

use App\Http\Resources\UserDto;
use App\Models\User;
use Exception;
use Ramsey\Uuid\Uuid;

readonly class UserService
{
    public function __construct(
        private AccountService $accountService,
    ) {}

    /**
     * @throws Exception
     */
    public function addUser(string $fullName, string $email): UserDto {
        $user = new User([
            'userId' => Uuid::uuid4(),
            'fullName' => $fullName,
            'email' => $email
        ]);

        $user->save();
        $this->accountService->addAccountForUser($user->userId);
        return $this->userToUserDto($user);
    }

    /**
     * @throws Exception
     */
    public function getUser(string $userId): UserDto {
        $user = User::query()->where('userId', $userId)->first();

        if (is_null($user)) {
            throw new Exception("User with id " . $userId . " not found");
        }

        return $this->userToUserDto($user);
    }

    private function userToUserDto(User $user): UserDto {
        return new UserDto($user->userId, $user->fullName, $user->email);
    }
}
