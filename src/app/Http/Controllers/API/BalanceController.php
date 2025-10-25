<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\UserBalanceService;
use Illuminate\Http\JsonResponse;
use Throwable;

class BalanceController extends Controller
{
    public function __construct(protected UserBalanceService $userBalanceService) {}

    /** Получение баланса */
    public function get(int $userId): JsonResponse
    {
        try {
            $response = $this->userBalanceService->getUserBalance($userId);
        } catch (Throwable $e) {
            return response()->json($e->getMessage(), $e->getCode(), [], JSON_PRETTY_PRINT);
        }

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    /** Начисление средств пользователю */
    public function deposit(): JsonResponse
    {
        $requestBody = request()->request;
        $rules = [
            'user_id' => ['required', 'int'],
            'amount'  => ['required', 'numeric', 'decimal:0,2'],
            'comment' => ['string'],
        ];

        try {
            validator($requestBody->all(), $rules)->validate();

            $this->userBalanceService->deposit(
                (int) $requestBody->get('user_id'),
                (float) $requestBody->get('amount'),
                $requestBody->get('comment')
            );
        } catch (Throwable $e) {
            return response()->json($e->getMessage(), $e->getCode() ?: 400, [], JSON_PRETTY_PRINT);
        }

        return response()->json('Success.', 200, [], JSON_PRETTY_PRINT);
    }

    /** Списание средств */
    public function withdraw(): JsonResponse
    {
        $requestBody = request()->request;
        $rules = [
            'user_id' => ['required', 'int'],
            'amount'  => ['required', 'numeric', 'decimal:0,2'],
            'comment' => ['string'],
        ];

        try {
            validator($requestBody->all(), $rules)->validate();

            $this->userBalanceService->withdraw(
                (int) $requestBody->get('user_id'),
                (float) $requestBody->get('amount'),
                $requestBody->get('comment'),
            );
        } catch (Throwable $e) {
            return response()->json($e->getMessage(), $e->getCode() ?: 400, [], JSON_PRETTY_PRINT);
        }

        return response()->json('Success.', 200, [], JSON_PRETTY_PRINT);
    }

    public function transfer(): JsonResponse
    {
        $requestBody = request()->request;
        $rules = [
            'from_user_id' => ['required', 'int'],
            'to_user_id'   => ['required', 'int'],
            'amount'       => ['required', 'numeric', 'decimal:0,2'],
            'comment'      => ['string'],
        ];

        try {
            validator($requestBody->all(), $rules)->validate();
            $this->userBalanceService->transfer(
                (int) $requestBody->get('from_user_id'),
                (int) $requestBody->get('to_user_id'),
                (float) $requestBody->get('amount'),
                $requestBody->get('comment'),
            );
        } catch (Throwable $e) {
            return response()->json($e->getMessage(), $e->getCode() ?: 400, [], JSON_PRETTY_PRINT);
        }

        return response()->json('Success.', 200, [], JSON_PRETTY_PRINT);
    }
}
