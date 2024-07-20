<?php

namespace App\Services\Auth;

use Exception;
use App\Helpers\Response;
use App\Cache\UserCache;
use App\Models\User;
use App\Services\TokenManagementService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Request;

class LoginService
{
  public static function handle($request, bool $skipVerification = false): object
  {
    $Response = new Response();
    $response = $Response::get();
    $loginType = object(config("data.loginType"));

    try {
      // RateLimiter::clear(self::throttleKey());
      self::checkTooManyFailedAttempts();

      $user = UserCache::ByEmail($request->email, true, !$skipVerification);

      if ($user) {
        $requestIsAdmin = $user->type === config("data.userType.admin") && stripos($request->url(), '/admin');

        if ($request->type === $loginType->manual) {
          $token = Auth::attempt($request->only(['email', 'password']));
        } else {
          // One time save google if user didn't create account with google auth
          if (empty($user->google_auth)) {
            $user->google_auth = $request->password;
            $user->save();
            $user->refresh();
          }
          $token = $request->password === $user->google_auth;
        }

        if ($token) {

          // Users cannot use the admin login route
          if ($user->type !== config("data.userType.admin") && stripos($request->url(), '/admin') !== false) {
            throw new Exception('Unauthorized Route', 401);
          }

          // Send 2FA OTP for admin
          if ($requestIsAdmin) {
            $response =  TokenManagementService::sendToken($user, config("data.tokenFor.adminLogin"), true);
          }

          $bearer = $requestIsAdmin ? self::respondWithToken($user, true) : self::respondWithToken($user);
          $user->remember_token = $bearer->access_token;
          $user->save();
          $user->makeHidden(['remember_token']);

          $user->image = asset($user->image);

          // response
          $response = $Response::set(
            [
              'message' => $requestIsAdmin  ? 'OTP Sent' : 'Login Successful',
              'data' =>
              [
                'user' => $requestIsAdmin  ? null : $user,
                'auth' => $bearer
              ]
            ],
            true
          );

          // Event::dispatch(new Authenticated('web', $user));
        } else throw new Exception('Invalid Email or Password');
      } else $response = UserCache::emptyResponse();
    } catch (\Throwable $th) {
      RateLimiter::hit(self::throttleKey(), 3600);
      $response = $Response::set(["message" => $th->getMessage(), 'code' => 'unauthorized']);
    }

    return $response;
  }

  public static function proxyLogin($request): object
  {
    $Response = new Response();
    $response = $Response::get();

    try {
      $user = UserCache::ByEmail($request->email);

      if ($user) {
        $response = $Response::set(
          [
            'data' => ['user' => $user, 'auth' => self::respondWithToken($user)]
          ],
          true
        );
        RateLimiter::clear(self::throttleKey());
      } else throw new Exception('Invalid User');
    } catch (\Throwable $th) {
      RateLimiter::hit(self::throttleKey(), 3600);
      $response = $Response::set(["message" => $th->getMessage(), 'code' => 'unauthorized']);
    }

    return $response;
  }

  public function verifyAdminOTP($request): object
  {
    $Response = new Response();
    $response = $Response::get();

    $user = UserCache::ByID((Auth::user())->id);
    $response = TokenManagementService::verifyOTP($user, $request->otp);
    if ($response->status) {
      $response = $Response::set(
        [
          'data' => ['user' => $user, 'auth' => self::respondWithToken($user, null, true)],
        ],
        true
      );
    }

    return $response;
  }

  public static function getBearer(User $user, $onetime = false)
  {
    return self::respondWithToken($user, $onetime);
  }

  private static function respondWithToken(User $user, $onetime = false, $isAdmin = false)
  {

    $tokenTypes =  object(config('data.tokenTypes'));
    $key = $onetime ? $tokenTypes->oneTimeToken : ($isAdmin ? $tokenTypes->adminToken  : $tokenTypes->defaultToken);

    return object([
      'access_token' => $user->createToken($key)->plainTextToken,
      'token_type' => $key,
      'expires_in' => config('sanctum.expiration')
    ]);
  }

  /**
   * Get the rate limiting throttle key for the request.
   *
   * @return string
   */
  public static function throttleKey()
  {
    return Request::ip();
  }

  /**
   * Ensure the login request is not rate limited.
   *
   * @return void
   */
  public static function checkTooManyFailedAttempts()
  {
    if (!RateLimiter::tooManyAttempts(self::throttleKey(), 3)) {
      return;
    }

    throw new Exception('Too many attempts. Try again in ' . RateLimiter::availableIn(self::throttleKey()) . 's');
  }
}
