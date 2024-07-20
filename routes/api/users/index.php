<?php

use App\Models\Company;
use App\Helpers\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TestController;

Route::get("/constants", function () {
    $Response = new Response();

    $response = $Response::set(
        [
            "data" => [
                "company" => Company::get()->makeHidden(['notifier', 'notification', 'email_channel']),
                "countries" => config("countries"),
                "banks" => config("banks"),
                "gender" => config("data.gender"),
                "couponType" => config("data.couponType"),
                "userType" => config('data.userType'),
                "loginType" => config("data.loginType"),
                "app_url" =>  config("app.frontend"),
                "userStatus" => config("data.userStatus"),
                "tagCategories" => config("data.tagCategories"),
                "transactionType" => config("data.transactionType"),
                "approval" => config("data.approval"),
            ]
        ],
        true
    );
    return response()->json($response, $response->code);
});

// Authentication
Route::group(['prefix' => '/auth'], function () {
    Route::post('/sign-up', [AuthController::class, 'register']);
    Route::post('/sign-in', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/resend-password-token', [AuthController::class, 'resendPasswordToken']);
    Route::post('/resend-email', [AuthController::class, 'resendEmail']);
    // Route::post('/validate-telegram-user', [AuthController::class, 'telegramUser']);

    // Protected Routes
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/check-auth', [AuthController::class, 'checkAuth']);
        Route::patch('/reset-password', [AuthController::class, 'resetPassword']);
        Route::patch('/verify-email', [AuthController::class, 'verifyEmail']);
        Route::post('/sign-out', [AuthController::class, 'logout']);
    });
});

// Protected Routes
Route::group(['middleware' => 'auth:sanctum'], function () {

    // Users
    Route::group(['prefix' => '/user'], function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::patch('/', [ProfileController::class, 'update']);
        Route::post('/change-password', [ProfileController::class, 'passwordChange']);
    });

    // Transactions
    Route::group(['prefix' => '/transaction'], function () {
        Route::get('/list', [TransactionController::class, 'index']);
    });

    // Notifications
    Route::group(['prefix' => '/notification'], function () {
        Route::get('/list', [NotificationController::class, 'index']);
        Route::post('/mark-all', [NotificationController::class, 'markAll']);
        Route::patch('/mark-one/{id}', [NotificationController::class, 'markOne']);
    });

    // Activity Logs
    Route::group(['prefix' => '/activity'], function () {
        Route::get('/list', [LogController::class, 'index']);
    });
});
// Content
Route::group(['prefix' => '/content'], function () {
    Route::get('/faq', [ContentController::class, 'index']);
    Route::get('/term', [ContentController::class, 'index']);
    Route::get('/policy', [ContentController::class, 'index']);
    Route::get('/testimonial', [ContentController::class, 'index']);
    Route::get('/team', [ContentController::class, 'index']);
    Route::get('/how-to', [ContentController::class, 'index']);
});

// test
Route::group(['prefix' => '/test'], function () {
    Route::get('/cache', [TestController::class, 'cache']);
    Route::get('/watermark', [TestController::class, 'watermark']);
});
