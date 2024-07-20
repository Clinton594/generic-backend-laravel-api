<?php

namespace App\Services\Auth;

use Exception;
use App\Helpers\Response;
use App\Cache\UserCache;
use App\Models\TokenVerification;
use App\Services\TokenManagementService;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

class PasswordResetService
{

  static function triggerMail($request): object
  {
    $Response = new Response;
    $response = $Response::get();

    $user = UserCache::ByEmail($request->email);
    if ($user)
      $response = TokenManagementService::sendToken($user, config('data.tokenFor.passwordReset'), true);
    else $response = UserCache::emptyResponse();

    if ($response->status) $response = $Response::set(
      [
        "message" => "Password reset mail sent",
        "data" => ["auth" => LoginService::getBearer($user, true)]
      ],
      true
    );

    return $response;
  }

  static function resendMail($request): object
  {
    $Response = new Response;
    $response = $Response::get();

    $user = UserCache::ByEmail($request->email);
    if ($user)
      $response = TokenManagementService::sendToken($user, config('data.tokenFor.passwordReset'), true);
    else $response = UserCache::emptyResponse();

    if ($response->status) $response = $Response::set(
      [
        "message" => "Password reset mail sent",
        "data" => ["auth" => LoginService::getBearer($user, true)]
      ],
      true
    );

    return $response;
  }

  static function reset($request): object
  {
    $Response = new Response;
    $response = $Response::get();

    try {
      $token = TokenVerification::findOrFail($request->token);
      $user = UserCache::ByID($token->user_id);

      // check if token is valid
      $response = TokenManagementService::verifyOTP($user, $token->OTP);

      if ($response->status) {
        $user->password =  Hash::make($request->password);

        // Automatically verify their email if not already verified
        if (!$user->email_verified) {
          $user->email_verified_at = Carbon::now()->format(config('data.dateFullFormat'));
        }

        // Delete all access tokens
        $user->tokens()->delete();

        if ($user->save()) {
          $response = $Response::set(
            [
              "message" => "Password reset successful",
            ],
            true
          );

          Event::dispatch(new PasswordReset($user));
        } else throw new Exception('Error Updating Password', 400);
      }
    } catch (\Throwable $th) {
      $response = $Response::set(["message" => $th->getMessage(), 'code' => getExceptionCode($th)]);
    }

    return $response;
  }
}
