<?php

namespace App\Http\Controllers;

class BaseController extends Controller
{
    public function __construct()
    {

        // force single-use-token to be used only once
        $this->middleware(function ($request, $next) {
            $user = $request->user();
            if ($user) {
                if ($user->currentAccessToken()->name === config('data.tokenTypes.oneTimeToken')) {
                    $user->currentAccessToken()->delete();
                }
            }
            return $next($request);
        });
    }
}
