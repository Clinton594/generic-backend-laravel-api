<?php

namespace App\Services\User;

use App\Helpers\Response;
use App\Models\User;

class UserShowService
{
    public static function handle($request)
    {

        $Response = new Response();
        $response = $Response::get();

        try {
            $user =  User::findOrfail($request->id);

            $user['signature'] = asset($user->signature);

            if ($user->type === config('data.userType.student')) {
                unset($user->signature);
            }

            $user['image'] = asset($user->image);
            $response = $Response::set(['data' => $user], true);
        } catch (\Throwable $th) {
            $response = $Response::set(['message' => $th->getMessage(), 'code' => getExceptionCode($th)]);
        }

        return $response;
    }
}
