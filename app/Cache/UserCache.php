<?php

namespace App\Cache;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

final class UserCache extends Cache
{

  static function ByEmail(string $email, bool $force_clear = false, bool $must_be_active = true): User | null
  {

    if ($force_clear) self::clear('users.' . $email);
    $user = self::remember('users.' . $email, config("data.cacheTime"), function () use ($email, $must_be_active) {
      return User::where("email", $email)->isActive($must_be_active)->first();
    });
    return ($must_be_active && $user && $user->status !== config('data.userStatus.active')) ? null : $user;
  }

  static function ByID(string $id, bool $force_clear = false, bool $must_be_active = true): User| null
  {
    if ($force_clear) self::clear('users.' . $id);
    $user =  self::remember('users.' . $id, config("data.cacheTime"), function () use ($id, $must_be_active) {
      return User::where("id", $id)->isActive($must_be_active)->first();
    });
    return ($must_be_active && $user && $user->status !== config('data.userStatus.active')) ? null : $user;
  }

  static function ByTelegram(string $username, bool $force_clear = false, bool $must_be_active = true): User| null
  {
    if ($force_clear) self::clear('users.' . $username);
    $user =  self::remember('users.' . $username, config("data.cacheTime"), function () use ($username, $must_be_active) {
      return User::where("telegram", $username)->isActive($must_be_active)->first();
    });
    return ($must_be_active && $user && $user->status !== config('data.userStatus.active')) ? null : $user;
  }

  static function remove(User $user)
  {
    self::clear('users.' . $user->id);
    self::clear('users.' . $user->email);
  }

  static function emptyResponse()
  {
    return object([
      "status" => false,
      "code" => 404,
      "message" => "User not found or inactive",
      "data" => [
        "redirect" => "/auth/verify-email",
        "action" => "Request OTP"
      ]
    ]);
  }
}
