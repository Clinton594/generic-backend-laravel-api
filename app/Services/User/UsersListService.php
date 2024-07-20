<?php

namespace App\Services\User;

use App\Models\User;
use App\Helpers\Response;

class UsersListService
{

  public static function handle($request)
  {

    $Response = new Response();
    $response = $Response::get();

    try {

      $builder =  User::filter($request)
        ->latest('created_at');

      $pagination = $builder->miniPaginate($request);

      $list  = collect($builder->getList())->map(function ($user) {
        if (!empty($user->image)) $user->image = asset($user->image);
        return $user;
      });

      $response = $Response::set(['data' => ['pagination' => $pagination, 'list' => $list]], true);
    } catch (\Throwable $th) {
      $response = $Response::set(['message' => $th->getMessage(), 'code' => getExceptionCode($th)]);
    }

    return $response;
  }
}
