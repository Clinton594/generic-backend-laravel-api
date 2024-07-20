<?php

namespace App\Services\GateWay;

use App\Helpers\Curl;
use App\Helpers\Paystack;
use App\Helpers\Response;
use App\Models\Transaction;
use Illuminate\Support\Str;


use Exception;


use App\Traits\CheckOwner;
use Illuminate\Support\Facades\Log;

class OxapayClient extends Curl
{

  private static $base_url = "https://api.oxapay.com";

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


  public static function initial(array $attributes, Transaction $transaction)
  {

    $Response = new Response();
    $response = $Response::get();

    $provider = config('data.paymentGateWays.oxapay');

    try {

      $oxapay = self::post(
        '/merchants/request',
        [
          "merchant" => config('services.oxapay.secret'),
          "currency" => config('data.currencyCodes.usd'),
          "payCurrency" => "USDT",
          'amount' => $attributes["fee"],
          "feePaidByPayer" => 1,
          "underPaidCover" => 10,
          "returnUrl" => (config('app.env') === 'local' ? config('app.url') : config('app.frontend'))
            . "/subscribe/verify?provider={$provider}",
          "description" => "Course Payment",
          "orderId" =>  $transaction->id, //transaction id or reference id
          "email" => $attributes['email'] //user email
        ]
      );

      if ($oxapay->code === 200 && $oxapay->body->message == "success") {

        $transaction->authorization_url = null;
        $transaction->reference = $oxapay->body->trackId;
        $transaction->save();

        $data = [
          "authorization_url" => $oxapay->body->payLink,
          "reference" => $oxapay->body->trackId
        ];

        $response = $Response::set(['data' => $data], true);
      } else {
        throw new Exception('something went wrong check your internet connection and try again');
      }
    } catch (\Throwable $th) {
      $response = $Response::set(["message" => $th->getMessage(), "code" => getExceptionCode($th)], false);
    }
    return $response;
  }

  public static function verifyPayment(array $attributes)
  {

    $Response = new Response();
    $response = $Response::get();

    $trackId = $attributes['trackId'] ?? $attributes['reference'];

    try {
      $oxapay = self::post(
        '/merchants/inquiry',
        [
          "merchant" => config('services.oxapay.secret'),
          "trackId" => $trackId,
        ]
      );

      if ($oxapay->code === 200 && $oxapay->body->message == "success") {
        $response = $Response::set(['data' => $oxapay->body], true);
      } else {
        throw new Exception('something went wrong check your internet connection and try again');
      }
    } catch (\Throwable $th) {
      $response = $Response::set(["message" => $th->getMessage(), "code" => getExceptionCode($th)], false);
    }
    return $response;
  }
}
