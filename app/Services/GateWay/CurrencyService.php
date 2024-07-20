<?php

namespace App\Services\GateWay;

use App\Helpers\Curl;
use App\Helpers\Response;
use App\Models\Company;
use Exception;

class CurrencyService extends Curl
{

  private static $base_url = "https://v6.exchangerate-api.com/v6/";


  public static function get(string $endpoint, array $param = [])
  {
    // self::initialize();
    $url = self::$base_url . $endpoint;
    return parent::get($url, $param);
  }

  public static function post(string $endpoint, array $param = [])
  {
    // self::initialize();
    $url = self::$base_url . $endpoint;
    return parent::post($url, $param);
  }

  public static function getExchangeRate()
  {
    $Response = new Response();
    $response = $Response::get();

    $api_key = config('services.exchangerate.secret');

    try {
      $rate = self::get("{$api_key}/latest/USD", []);

      if ($rate->code === 200 && $rate->body->result == "success") {
        $nairaRate = $rate->body->conversion_rates->NGN;

        Company::first()->update(['dollar_rate' => $nairaRate]);

        $response = $Response::set(['data' => $nairaRate]);
      } else {
        throw new Exception('something went wrong check your internet connection and try again');
      }
    } catch (\Throwable $th) {
      $response = $Response::set(["message" => $th->getMessage(), "code" => getExceptionCode($th)], false);
    }
    return $response;
  }

  public static function convertNairaToDollars($dollarRate, $amountInNaira)
  {
    return $amountInNaira / $dollarRate;
  }
}
