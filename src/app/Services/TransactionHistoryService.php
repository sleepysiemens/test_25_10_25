<?php

namespace App\Services;

use App\Models\TransactionHistory;

class TransactionHistoryService
{
    public static function logDeposit(int $userId, float $amount, string|null $comment = null): void
    {
        TransactionHistory::query()->create(
            [
                'type'    => 'deposit',
                'to'      => $userId,
                'amount'  => $amount,
                'comment' => $comment,
            ]
        );
    }

    public static function logWithdraw(int $userId, float $amount, string|null $comment = null): void
    {
        TransactionHistory::query()->create(
            [
                'type'    => 'deposit',
                'from'    => $userId,
                'amount'  => $amount,
                'comment' => $comment,
            ]
        );
    }

    public static function logTransfer(int $fromUserId, int $toUserId,  float $amount, string|null $comment = null): void
    {
        TransactionHistory::query()->create([
            'type'    => 'transfer',
            'from'    => $fromUserId,
            'to'      => $toUserId,
            'amount'  => $amount,
            'comment' => $comment,
        ]);
    }
}
