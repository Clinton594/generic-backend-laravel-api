<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Models\Course;
use App\Services\Course\WatermarkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class TestController extends BaseController
{
    private $watermarkService;

    function __construct(WatermarkService $watermarkService)
    {
        $this->watermarkService = $watermarkService;
    }

    function watermark(Request $request)
    {
        try {
            $course = Course::findOrFail($request->course_id);
            $res = $this->watermarkService->handle($course);
        } catch (\Throwable $th) {
            $res = Response::set(['message' => $th->getMessage(), "code" => getExceptionCode($th)]);
        }

        return response()->json($res, $res->code);
    }

    function cache(Request $request)
    {
        $executed = RateLimiter::attempt('send-message', 2, function () {
            return response()->json([
                'test' => 'test success'
            ]);
        });
        if (!$executed) {
            return response()->json('Too many attempts!');
        }
        return $executed;
    }
}
