<?php

namespace App\Services\Auth;

use Exception;
use App\Helpers\Response;
use App\Cache\UserCache;
use App\Services\TokenManagementService;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;

class EmailVerificationService
{

  static function verify($request): object
  {
    $Response = new Response;
    $response = $Response::get();

    try {
      $user = UserCache::ByID((Auth::user())->id, false, false);
      $response = TokenManagementService::verifyOTP($user, $request->otp);


      // Error from verify otp
      if (!$response->status) throw new Exception($response->message, 400);

      // verify Email
      $user->email_verified_at = Carbon::now()->format(config('data.dateFullFormat'));

      // only approve user profiles who are pending
      if ($user->status === config('data.userStatus.inactive')) {
        $user->status = config('data.userStatus.active');
      }
      $user->save();

      // Dispatch an email verified event
      Event::dispatch(new Verified($user));
      return $Response::set(["message" => 'Verification Successful'], true);
    } catch (\Throwable $th) {
      $response = $Response::set(["message" => $th->getMessage(), 'code' => getExceptionCode($th)]);
    }

    return $response;
  }

  static function resend($request): object
  {
    $Response = new Response;
    $response = $Response::get();

    $user = UserCache::ByEmail($request->email, true, false);
    $response = TokenManagementService::sendToken($user, 'EMAIL_VERIFICATION', true);
    if ($response->status) {
      $response = $Response::set([
        "data" => ["auth" => LoginService::getBearer($user, true)]
      ], true);
    }

    return $response;
  }
}
