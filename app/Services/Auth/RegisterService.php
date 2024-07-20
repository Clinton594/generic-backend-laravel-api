<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Helpers\Response;
use App\Models\Referral;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RegisterService
{

  public function handle($request): object
  {
    $Response = new Response;
    $response = $Response::get();
    $loginType = object(config("data.loginType"));

    try {
      $userType = object(config('data.userType'));
      $userStatus = object(config('data.userStatus'));

      // Format fields and password
      $feilds = $request->only(['email', 'first_name', 'last_name', 'phone', 'password', 'type', 'image', 'country']);
      if ($request->type === $loginType->google) {
        $feilds['google_auth'] = $feilds['password'];
        $feilds['status'] = $userStatus->active;
        $feilds['email_verified_at'] = Carbon::now();
      }

      $feilds['password'] = Hash::make($feilds['password']);

      $feilds = array_merge(
        $feilds,
        [
          "type" => $userType->user,
          'user_name' => strtoupper(substr(explode("@", $request->email)[0], 0, 7) . random(3))
        ]
      );

      // Create the user record
      $user = new User;
      foreach ($feilds as $key => $feild) {
        $user->{$key} = $feild;
      }
      $user->save();

      // handle refereral
      if ($request->has('referral')) {
        $referee = User::where('user_name', $request->referral)->first();
        if ($referee) {
          $referral = new Referral;
          $referral->referred_id = $user->id;
          $referral->referral_id = $referee->id;
          $referral->save();
        }
      }

      // Registration Activity handled by User Observer @created

      // Auto login the user
      $response = LoginService::handle($request, true, true);
      if ($response->status) {
        $response->message = "Welcome {$request->first_name}";
      }
    } catch (\Throwable $th) {
      $response = $Response::set(["message" => $th->getMessage()]);
    }

    return   $response;
  }
}
