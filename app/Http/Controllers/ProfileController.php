<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\User\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ProfileController extends BaseController
{
    private static $profileService;

    function __construct(ProfileService $profileService)
    {
        self::$profileService = $profileService;
        parent::__construct();
    }

    // current logged in user detail
    function show(): JsonResponse
    {
        $reponse = self::$profileService->show();
        return response()->json($reponse, $reponse->code);
    }

    // Update current logged in user profile
    function update(ProfileUpdateRequest $request): JsonResponse
    {
        $reponse = self::$profileService->update($request);
        return response()->json($reponse, $reponse->code);
    }

    // Change user password
    function passwordChange(ResetPasswordRequest $request): JsonResponse
    {
        $reponse = self::$profileService->changePassword($request);
        return response()->json($reponse, $reponse->code);
    }

    function uploadImages(): JsonResponse
    {
        return object([]);
    }
}
