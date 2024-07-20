<?php

namespace App\Http\Controllers;

use App\Cache\UserCache;
use App\Helpers\Response;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
// Requests
use App\Http\Requests\LoginRequest;
use App\Http\Requests\EmailOnlyRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UsernameOnlyRequest;
use App\Http\Requests\OTPRequest;
// Services
use App\Services\Auth\EmailVerificationService;
use App\Services\Auth\LoginService;
use App\Services\Auth\PasswordResetService;
use App\Services\Auth\RegisterService;

class AuthController extends BaseController
{
    private static RegisterService  $RegisterService;
    private static LoginService  $LoginService;
    private static EmailVerificationService  $VerifyEmailService;
    private static PasswordResetService  $PasswordResetService;

    public function __construct(
        RegisterService $RegisterService,
        LoginService $LoginService,
        EmailVerificationService $VerifyEmailService,
        PasswordResetService $PasswordResetService
    ) {
        self::$RegisterService = $RegisterService;
        self::$LoginService = $LoginService;
        self::$VerifyEmailService = $VerifyEmailService;
        self::$PasswordResetService = $PasswordResetService;
    }

    // Register Function
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = self::$RegisterService->handle($request);
        return response()->json($data, $data->code);
    }

    // Login Function
    public function login(LoginRequest $request): JsonResponse
    {
        $data = self::$LoginService->handle($request);
        return response()->json($data, $data->code);
    }

    // proxy Login Function, Login on behalf of a student user as a super admin
    public function proxyLogin(EmailOnlyRequest $request): JsonResponse
    {
        $data = self::$LoginService->proxyLogin($request);
        return response()->json($data, $data->code);
    }

    // Resend user Email Code
    public function resendEmail(Request $request): JsonResponse
    {
        $data = self::$VerifyEmailService->resend($request);
        return response()->json($data, $data->code);
    }

    // Verify user Email
    public function verifyEmail(OTPRequest $request): JsonResponse
    {
        $data = self::$VerifyEmailService->verify($request);
        return response()->json($data, $data->code);
    }

    // Admin Verify Otp Function
    public function verifyOTP(OTPRequest $request): JsonResponse
    {
        $data = self::$LoginService->verifyAdminOTP($request);
        return response()->json($data, $data->code);
    }

    // Trgger Forgot password
    public function forgotPassword(EmailOnlyRequest $request): JsonResponse
    {
        $data = self::$PasswordResetService->triggerMail($request);
        return response()->json($data, $data->code);
    }

    // Trgger REsend  passord Token
    public function resendPasswordToken(EmailOnlyRequest $request): JsonResponse
    {
        $data = self::$PasswordResetService->resendMail($request);
        return response()->json($data, $data->code);
    }

    // Reset  passord Token
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $data = self::$PasswordResetService->reset($request);
        return response()->json($data, $data->code);
    }

    // check Auth
    public function checkAuth(): JsonResponse
    {
        $Response = new Response();
        $response = $Response::set(['message' => 'Authenticated'], true);
        return response()->json($response, $response->code);
    }

    // Reset  passord Token
    // public function telegramUser(UsernameOnlyRequest $request): JsonResponse
    // {
    //     $Response = new Response();
    //     $response = $Response::get();
    //     $user = UserCache::ByTelegram($request->username);
    //     $response = $user ? $Response::set([], true) : $Response::set(['message' => "Not Found!", "code" => 404]);
    //     return response()->json($response, $response->code);
    // }

    // Logout Function
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            UserCache::remove($user);
            $request->user()->currentAccessToken()->delete();
        }
        return response()->json(["message" => "Successfully logged out", "status" => true], 200);
    }
}
