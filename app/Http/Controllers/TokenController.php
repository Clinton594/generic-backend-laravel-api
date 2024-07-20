<?php

namespace App\Http\Controllers;

use App\Services\TokenManagementService;
use Illuminate\Http\Request;

class TokenController extends BaseController
{

    private $tokenManager;
    function __construct(TokenManagementService $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = $this->tokenManager->list($request);
        return response()->json($response, $response->code);
    }
}
