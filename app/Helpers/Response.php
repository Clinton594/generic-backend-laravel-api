<?php

namespace App\Helpers;

use stdClass;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNan;

class Response
{
    static $code;
    static $status;
    static $message;
    static $data;
    static $codes;

    function __construct()
    {
        self::$codes = object(config("response"));
        self::$code = self::$codes->bad_request;
        self::$message = "";
        self::$status = false;
        self::$data = new stdClass;
    }

    public static function set(array $object = [], $success = false): object
    {
        $staticbuild = ['status' => false, 'code' => 400, 'data' => null, 'message' => ''];
        if ($success) {
            self::$status = true;
            self::$code = self::$codes->{'success'};
            self::$message = "SUCCESSFUL";
        }
        foreach ($object as $key => $value) {
            if (isset(self::${$key})) {
                if ($key === "code") self::$code = intval($value) ? $value : self::$codes->{$value};
                else self::${$key} = $value;
            } else if (isset($staticbuild[$key])) {
                $staticbuild[$key] = $value;
            }
        }

        $response = self::get();
        return !empty($response->code) ? $response : object($staticbuild);
    }


    public static function get()
    {
        return object(["status" => self::$status, "code" => self::$code, "message" => self::$message, "data" => self::$data]);
    }




    public static function validatorResponse(array $request = [], array $parameters = [])
    {
        $validated = Validator::make($request, $parameters);

        if ($validated->fails()) {

            $errors = $validated->errors()->first();

            return object(["status" => false, "message" => $errors]);
        } else return object(["status" => true]);
    }
}
