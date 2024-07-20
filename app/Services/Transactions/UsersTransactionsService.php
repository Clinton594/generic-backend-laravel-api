<?php

namespace App\Services\Transactions;

use App\Cache\UserCache;
use App\Helpers\Response;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class UsersTransactionsService
{
    public static function handle($request): mixed
    {
        $Response = new Response();
        $response = $Response::get();


        try {
            $user = UserCache::ByID(Auth::user()->id);
            if ($user) {

                $transactions = Transaction::where('student_id', $user->id)
                    ->get(['tx_type', 'reference', 'description', 'amount', 'due_date', 'status', 'created_at']);

                $response = $Response::set(["data" => $transactions], true);
            } else {
                return UserCache::emptyResponse();
            }
        } catch (\Throwable $th) {
            $response = $Response::set(["message" => $th->getMessage(), "code" => getExceptionCode($th)]);
        }

        return $response;
    }
}
