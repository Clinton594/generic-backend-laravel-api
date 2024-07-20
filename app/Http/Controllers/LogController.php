<?php

namespace App\Http\Controllers;

use App\Services\Notify\AllLogsService;
use App\Services\Notify\Logger;
use Illuminate\Http\Request;

class LogController extends BaseController
{
    private static $Service;
    private static $AllLogs;

    public function __construct(Logger $Service, AllLogsService $AllLogs)
    {
        self::$Service = $Service;
        self::$AllLogs = $AllLogs;
    }

    public function list(Request $request)
    {
        $response = self::$AllLogs->handle($request);
        return response()->json($response, $response->code);
    }

    public function error(Request $request)
    {
        $response = self::$Service->error($request);
        return response()->json($response, $response->code);
    }
}
