<?php

namespace App\Http\Controllers;

use App\Services\Notify\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends BaseController
{
    private static $Service;

    public function __construct(NotificationService $Service)
    {
        self::$Service = $Service;
    }


    function index(Request $request)
    {
        $response = self::$Service->notificationList($request);
        return response()->json($response, $response->code);
    }

    function markOne(Request $request)
    {
        $response = self::$Service->markOne($request);
        return response()->json($response, $response->code);
    }


    function markAll(Request $request)
    {
        $response = self::$Service->markAll($request);
        return response()->json($response, $response->code);
    }
}
