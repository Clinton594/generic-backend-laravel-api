<?php

namespace App\Services\Transactions;

use App\Helpers\Response;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class AllTransactionsService
{
    public static function handle($request)
    {

        $Response = new Response();
        $response = $Response::get();
        $limit = intval($request->limit) ?? config('data.paginate10');

        try {

            $builder =  Transaction::leftJoin('users', 'transactions.student_id', '=', 'users.id')
            ->select('transactions.*', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS display_name"))
              ->latest('created_at')
              ->paginate($limit, ['*'], 'page');

            $pagination = [
              "current" => $builder->currentPage(),
              "total" => $builder->lastPage()
            ];

            $list  = $builder->getCollection();

            $response = $Response::set(['data' => ['pagination' => $pagination, 'list' => $list]], true);
        } catch (\Throwable $th) {
            $response = $Response::set(['message' => $th->getMessage(), 'code' => getExceptionCode($th)]);
        }

        return $response;
    }
}
