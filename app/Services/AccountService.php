<?php

namespace App\Services;

use App\Http\Resources\AccountDto;
use App\Models\Account;
use Exception;
use Ramsey\Uuid\Uuid;

readonly class AccountService {
    public function addAccountForUser(string $userId): AccountDto {
        $account = new Account([
            'accountId' => Uuid::uuid4(),
            'userId' => $userId,
            'balance' => 0.0,
        ]);

        $account->save();
        return $this->accountToAccountDto($account);
    }

    public function getAccountForUser(string $userId): AccountDto {
        $account = Account::query()->where('userId', $userId)->first();

        if (is_null($account)) {
            throw new Exception("Account not found for user " . $userId);
        }

        return $this->accountToAccountDto($account);
    }

    /**
     * @throws Exception
     */
    public function decreaseBalance(string $accountId, float $amount): AccountDto {
        $account = $this->getAccountById($accountId);

        $currentBalance = $account->balance;
        $account->balance = $currentBalance - $amount;
        $account->save();

        return $this->accountToAccountDto($account);
    }

    /**
     * @throws Exception
     */
    public function increaseBalance(string $accountId, float $amount): AccountDto {
        $account = $this->getAccountById($accountId);

        $currentBalance = $account->balance;
        $account->balance = $currentBalance + $amount;
        $account->save();

        return $this->accountToAccountDto($account);
    }

    private function accountToAccountDto(Account $account): AccountDto {
        return new AccountDto($account->accountId, $account->userId, $account->balance);
    }

    private function getAccountById(string $accountId): Account {
        $account = Account::query()->where('accountId', $accountId)->first();

        if (is_null($account)) {
            throw new \Exception("Account with id " . $accountId . " not found");
        }

        return $account;
    }
}
