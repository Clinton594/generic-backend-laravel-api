<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\Transactions\AllTransactionsService;
use App\Services\Transactions\GetTransactionService;
use App\Services\Transactions\UsersTransactionsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends BaseController
{
    private static UsersTransactionsService  $usersTransactions;
    private static GetTransactionService $getTransaction;
    private static AllTransactionsService $allTransaction;


    public function __construct(UsersTransactionsService $usersTransactions, GetTransactionService $getTransaction, AllTransactionsService $allTransaction)
    {
        self::$usersTransactions = $usersTransactions;
        self::$getTransaction = $getTransaction;
        self::$allTransaction = $allTransaction;
    }


    public function index(Request $request): JsonResponse
    {
        $data = self::$usersTransactions->handle($request);
        return response()->json($data, $data->code);
    }

    // Used by Admin
    public function user(UserRequest $request): JsonResponse
    {
        $data = self::$getTransaction->handle($request);
        return response()->json($data, $data->code);
    }


    public function list(Request $request): JsonResponse
    {
        $data = self::$allTransaction->handle($request);
        return response()->json($data, $data->code);
    }
}
