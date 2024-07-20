<?php

namespace App\Services\Transactions;

use App\Helpers\Response;
use App\Models\Transaction;

class GetTransactionService
{
    public function handle($request)
    {
        $Response = new Response();
        $response = $Response::get();

        try {
            $transactions = Transaction::where('student_id', $request->user_id)
                ->get(['tx_type', 'reference', 'description', 'amount', 'due_date', 'status', 'created_at']);

            $response = $Response::set(["data" => $transactions], true);
        } catch (\Throwable $th) {
            $response = $Response::set(["message" => $th->getMessage(), "code" => getExceptionCode($th)]);
        }

        return $response;
    }
}
