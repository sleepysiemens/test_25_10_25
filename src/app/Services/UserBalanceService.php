<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\UserBalance;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserBalanceService
{
    public function __construct(protected TransactionHistoryService $transactionHistoryService) {}

    /**
     * @throws Throwable
     */
    public function getUserBalance(int $userId): array
    {
        $user = $this->getUserWithBalance($userId);

        throw_if(! $user, new Exception('User not found.', 400));

        $balance = $user->balance;

        return [
            'balance'  => $balance->balance ?? 0,
            'currency' => $balance->currency ?? 'rub'
        ];
    }

    public function deposit(int $userId , float $amount, string|null $comment = ''): void
    {
        DB::transaction(fn() => $this->depositTransaction($userId, $amount));
        $this->transactionHistoryService::logDeposit($userId, $amount, $comment);
    }

    public function withdraw(int $userId , float $amount, string|null $comment = ''): void
    {
        DB::transaction(fn() => $this->withdrawTransaction($userId, $amount));
        $this->transactionHistoryService::logWithdraw($userId, $amount, $comment);

    }

    public function transfer(int $fromUserId, int $toUserId, float $amount, string|null $comment = ''): void
    {
        DB::transaction(function () use ($fromUserId, $toUserId, $amount) {
            self::withdrawTransaction($fromUserId, $amount);
            self::depositTransaction($toUserId, $amount);
        });
        $this->transactionHistoryService::logTransfer($fromUserId, $toUserId, $amount, $comment);
    }

    /**
     * @throws Throwable
     */
    private static function depositTransaction(int $userId, float $amount): void
    {
        $user = self::getUserWithBalance($userId);

        throw_if(! $user, new Exception('User not found.', 400));

        $balance = $user->balance;

        if (! $balance) {
            UserBalance::query()->create([
                'user_id' => $userId,
                'balance' => $amount,
            ]);

            return;
        }

        $balance->balance += $amount;
        $balance->save();
    }

    /**
     * @throws Throwable
     */
    private static function withdrawTransaction(int $userId, float $amount): void
    {
        $user = self::getUserWithBalance($userId);

        throw_if(! $user, new Exception('User not found.', 400));

        $balance = $user->balance;

        throw_if(! $balance, new Exception('Balance is empty.', 422));

        throw_if($balance->balance < $amount, new Exception('Not enough funds.', 422));

        $balance->balance -= $amount;
        $balance->save();
    }

    /**
     * @throws Throwable
     */
    private static function getUserWithBalance(int $userId): User|null
    {
        return User::query()
            ->select(['id'])
            ->where('id', $userId)
            ->with('balance')
            ->first();
    }
}
