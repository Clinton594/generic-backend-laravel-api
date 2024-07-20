<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\User\UserShowService;
use App\Services\User\UserUpdateService;
use App\Services\User\UsersListService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class UserController extends BaseController
{
    private static $usersListService;
    private static $userShowService;
    private static $userUpdateService;

    function __construct(
        UsersListService $usersListService,
        UserShowService $userShowService,
        UserUpdateService $userUpdateService,
    ) {
        self::$usersListService = $usersListService;
        self::$userShowService = $userShowService;
        self::$userUpdateService = $userUpdateService;
    }


    // get list of users
    public function list(Request $request): JsonResponse
    {
        $reponse = self::$usersListService->handle($request);
        return response()->json($reponse, $reponse->code);
    }

    // Get user detail by id
    public function show(Request $request): JsonResponse
    {
        $data = self::$userShowService->handle($request);
        return response()->json($data, $data->code);
    }

    // Get user detail by id
    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        $data = self::$userUpdateService->handle($request);
        return response()->json($data, $data->code);
    }
}
