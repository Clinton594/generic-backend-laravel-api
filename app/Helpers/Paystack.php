<?php

namespace App\Helpers;

use App\Helpers\Curl;

class Paystack extends Curl
{
  private static $base_url = "https://api.paystack.co";

  function __construct()
  {
    $key = env("PAYSTACK_API_KEY");
    parent::setHeaders(["Authorization: Bearer {$key}"]);
  }

  public static function get(string $endpoint, array $param = [])
  {
    $url = self::$base_url . $endpoint;
    return parent::get($url, $param);
  }

  public static function post(string $endpoint, array $param = [])
  {
    $url = self::$base_url . $endpoint;
    return parent::post($url, $param);
  }
}
