<?php

namespace App\Services\GateWay;

use App\Helpers\Curl;
use App\Helpers\Paystack;
use App\Helpers\Response;
use App\Models\Transaction;

use Exception;


use App\Traits\CheckOwner;
use Illuminate\Support\Facades\Log;

class PaystackClient extends Curl
{

  private static $base_url = "https://api.paystack.co";

  // private static $initialized = false;


  private const DEFAULT_ATTRIBUTES = [
    "fee" => null,
    "percentage" => null,
    "email" => null,
    "currency" => null
  ];


  public function __construct()
  {
    $key = config('services.paystack.secret'); // use config() for better practice
    if (!$key) {
      throw new Exception("Paystack API key is not set in the environment configuration.");
    }
    parent::setHeaders(["Authorization: Bearer {$key}"]);
  }


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


  public static function initial(array $attributes = self::DEFAULT_ATTRIBUTES, Transaction $transaction)
  {

    $Response = new Response();
    $response = $Response::get();

    try {

      $paystack =  self::post(
        '/transaction/initialize',
        [
          "email" => $attributes['email'],
          "amount" => toKobo($attributes["fee"]),
          // on local, callback to redirect to api endpoint/subscribe instead of frontend url
          "callback_url" => (config("app.env") === 'local' ? config("app.url") : config("app.frontend")) . "/subscribe/verify?provider=PAYSTACK",
          "currency" => $attributes['currency'],
          "metadata" => ["transaction_id" => $transaction->id]
        ]
      );

      if (in_array($paystack->code, [200, 201])) {
        $authorization_url = $paystack->body->data->authorization_url;
        $reference = $paystack->body->data->reference;

        $transaction->authorization_url = $authorization_url;
        $transaction->reference = $reference;
        $transaction->save();

        $data = [
          "authorization_url" => $authorization_url,
          "reference" => $reference
        ];
        $response = $Response::set(['data' => $data], true);
      } else {
        throw new Exception($paystack->body->message ?? 'something went wrong check your internet connection and try again');
      }
    } catch (\Throwable $th) {
      $response = $Response::set(["message" => $th->getMessage(), "code" => getExceptionCode($th)], false);
    }
    return $response;
  }

  public static function verifyBankDetail($bank_code, $account_number)
  {
    $Response = new Response();
    $response = $Response::get();

    try {
      $Paystack = new Paystack();
      $paystack =  $Paystack::get('/bank/resolve', [
        "account_number" => $account_number,
        "bank_code" => $bank_code
      ]);


      if (in_array($paystack->code, [200, 201])) {

        $response = $Response::set(['data' => $paystack->body->data], true);
      } else {
        throw new Exception($paystack->body->message ?? 'something went wrong check your internet connection and try again');
      }
    } catch (\Throwable $th) {
      $response = $Response::set(["message" => $th->getMessage(), "code" => getExceptionCode($th)], false);
    }
    return $response;
  }
}
