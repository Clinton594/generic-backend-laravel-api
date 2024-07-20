<?php

namespace App\Helpers;

class Curl
{
  static $return_headers = false;
  static $headers = [
    "Content-Type: application/json",
    "Accept: application/json",
  ];

  protected static function setHeaders($array = [])
  {
    foreach ($array as $value) {
      array_push(self::$headers, $value);
      self::$headers = array_unique(self::$headers);
    }
  }

  protected static function get(string $endpoint, array $param = [])
  {
    // Build Param Query
    $headers = [];
    $query = "";
    if (count($param)) {
      $query = self::array_serialize($param);
    }

    // Initialize CURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint . $query);
    curl_setopt($ch, CURLOPT_HTTPHEADER, self::$headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    if (self::$return_headers) {
      curl_setopt(
        $ch,
        CURLOPT_HEADERFUNCTION,
        function ($curl, $header) use (&$headers) {
          $len = strlen($header);
          $header = explode(':', $header, 2);
          if (count($header) < 2) // ignore invalid headers
            return $len;

          $headers[strtolower(trim($header[0]))][] = trim($header[1]);

          return $len;
        }
      );
    }
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return (object)["headers" => $headers, "body" => json_decode($response), "code" => $httpCode];
  }

  protected static function post(string $endpoint, array $param = [])
  {

    // see([$endpoint, self::$headers, $param]);

    // Initialize CURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_HTTPHEADER, self::$headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    curl_setopt($ch, CURLOPT_POST, count($param));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));

    // Build Param Query
    $headers = [];
    if (self::$return_headers) {
      curl_setopt(
        $ch,
        CURLOPT_HEADERFUNCTION,
        function ($curl, $header) use (&$headers) {
          $len = strlen($header);
          $header = explode(':', $header, 2);
          if (count($header) < 2) // ignore invalid headers
            return $len;

          $headers[strtolower(trim($header[0]))][] = trim($header[1]);

          return $len;
        }
      );
    }
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return (object)["headers" => $headers, "body" => json_decode($response), "code" => $httpCode];
  }

  private static function array_serialize(array $array = array()): string
  {
    $build = [];
    if (!empty($array)) {
      foreach ($array as $key => $val) {
        $key = trim($key);
        $val = trim(urlencode($val));
        $build[] = "$key=$val";
      }
    }
    return "?" . implode("&", $build);
  }
}
