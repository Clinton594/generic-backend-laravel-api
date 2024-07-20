<?php

namespace App\Services\User;

use Exception;
use App\Cache\UserCache;
use App\Helpers\Response;
use App\Services\Auth\LoginService;
use App\Services\Image\ImageManagementService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class ProfileService extends LoginService
{

  function show(): object
  {
    $Response = new Response();
    $response = $Response::get();

    try {
      $user = UserCache::ByID(Auth::user()->id);
      if (!$user) return UserCache::emptyResponse();

      $user->image = asset($user->image);

      $response =  $Response::set(["data" => $user], true);
    } catch (\Throwable $th) {
      $response =  $Response::set(["message" => $th->getMessage(), 'code' => getExceptionCode($th)]);
    }

    return $response;
  }

  // function update($request): object
  // {
  //   $Response = new Response();
  //   $response = $Response::get();

  //   try {
  //     $user = UserCache::ByID(Auth::user()->id);
  //     if ($user) {

  //       $fields = $request->all();
  //       if (!empty($fields)) {

  //         if (!empty($fields['email'])) unset($fields['email']);

  //         $user->update($fields);

  //         $response =  $Response::set(["data" => $user->refresh()], true);
  //       } else throw new Exception('Parameters are Empty', 400);
  //     } else return UserCache::emptyResponse();
  //   } catch (\Throwable $th) {
  //     $response =  $Response::set(["message" => $th->getMessage(), 'code' => getExceptionCode($th)]);
  //   }

  //   return $response;
  // }

  function update($request): object
  {
    $Response = new Response();
    $response = $Response::get();

    try {
      $user = UserCache::ByID(Auth::user()->id);
      if ($user) {
        $fields = $request->only(["first_name", "last_name", "phone", "image", "country", "type"]);
        if (!empty($fields)) {

          if (!empty($fields["image"])) {
            $fields["image"] = ImageManagementService::processImageField($request, $user);
          }

          $user->update($fields);
          $user = $user->refresh();

          $user->image = empty($user->image) ? null : asset($user->image);

          $response = $Response::set(["data" => $user], true);
        } else {
          throw new Exception('Parameters are Empty', 400);
        }
      } else {
        return UserCache::emptyResponse();
      }
    } catch (\Throwable $th) {
      $response = $Response::set(["message" => $th->getMessage(), 'code' => getExceptionCode($th)]);
    }

    return $response;
  }


  function changePassword($request): object
  {
    $Response = new Response();
    $response = $Response::get();

    try {
      parent::checkTooManyFailedAttempts();
      $user = UserCache::ByID(Auth::user()->id);
      if ($user) {


        if (Hash::check($request->old_password,  $user->password)) {
          $user->password =  Hash::make($request->password);
          $user->save();
          $response =  $Response::set(["data" => $user, "message" => "Password changed successfuly"], true);
        } else {
          RateLimiter::hit(parent::throttleKey(), 3600);
          throw new Exception('Authorization failed', 400);
        }
      } else return UserCache::emptyResponse();
    } catch (\Throwable $th) {
      $response =  $Response::set(["message" => $th->getMessage(), 'code' => getExceptionCode($th)]);
    }

    return $response;
  }
}
