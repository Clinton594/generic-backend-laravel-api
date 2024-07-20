<?php

namespace App\Services\User;

use App\Helpers\Response;
use App\Models\User;
use App\Services\Image\ImageManagementService;
use Exception;

class UserUpdateService
{
    public static function handle($request)
    {

        $Response = new Response();
        $response = $Response::get();

        try {
            $user =  User::findOrfail($request->id);

            $fields = $request->all();

            if (!empty($fields["image"])) {
                $fields["image"] = ImageManagementService::processImageField($request, $user);
                if ($fields["image"] === "") unset($fields["image"]);
            }

            if ($user->type === config('data.userType.student')) unset($fields["signature"]);

            if (!empty($fields["signature"])) {
                $fields["signature"] = ImageManagementService::processImageField($request, $user,  "signature", "signature");
                if ($fields["signature"] === "") unset($fields["signature"]);
            }

            $columns = array_keys($user->getAttributes());
            foreach ($fields as $key => $value) {
                if (in_array($key, $columns)) $user->{$key} = $value;
            }

            $user->save();
            $user = $user->refresh();

            $user['signature'] = empty($user->signature) ? null : asset($user->signature);
            if ($user->type === config('data.userType.student')) unset($user['signature']);
            $user['image'] = empty($user->image) ? null : asset($user->image);

            $response = $Response::set(['data' => $user], true);
        } catch (\Throwable $th) {
            $response = $Response::set(['message' => $th->getMessage(), 'code' => getExceptionCode($th)]);
        }
        return $response;
    }
}
