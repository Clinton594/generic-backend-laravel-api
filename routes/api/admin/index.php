<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PatchController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Admin Login
Route::group(['prefix' => '/auth'], function () {
    Route::post('/sign-in', [AuthController::class, 'login']);
});

// Protected admnin routes
Route::group(['middleware' => ['auth:sanctum', 'adminAccess']], function () {
    // auth
    Route::group(['prefix' => '/auth'], function () {
        Route::patch('/verify-otp', [AuthController::class, 'verifyOTP']);
        Route::get('/proxy-login', [AuthController::class, 'proxyLogin']);
    });

    // error logs
    Route::group(['prefix' => '/error-logs'], function () {
        Route::get('/list', [LogController::class, 'error']);
    });

    // OTP Tokens
    Route::group(['prefix' => '/tokens'], function () {
        Route::get('/list', [TokenController::class, 'index']);
    });

    // users
    Route::group(['prefix' => '/users'], function () {
        Route::get('/', [UserController::class, 'list']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::patch('/{id}', [UserController::class, 'update']);
    });

    // Activity Logs
    Route::group(['prefix' => '/activity'], function () {
        Route::get('/', [LogController::class, 'list']);
    });

    // coupons
    Route::group(['prefix' => '/coupon'], function () {
        Route::get('/', [CouponController::class, 'list']);
        Route::post('/create', [CouponController::class, 'create']);
        Route::patch('/update', [CouponController::class, 'update']);
    });

    // transactions
    Route::group(['prefix' => '/transactions'], function () {
        Route::get('/', [TransactionController::class, 'list']);
        Route::get('/', [TransactionController::class, 'user']);
    });

    // model patches
    Route::group(['prefix' => '/patch'], function () {
        Route::get('/populate-lectures', [PatchController::class, 'populateLectures']);
        Route::get('/populate-curriculum', [PatchController::class, 'populateCurriculum']);
    });

    // Courses
    Route::group(['prefix' => '/course'], function () {
        Route::delete('/delete/{id}', [CourseController::class, 'delete']);
        Route::post('/create', [CourseController::class, 'create']);
        Route::get('/list', [CourseController::class, 'adminIndex']);
        Route::get('/{slug}', [CourseController::class, 'show']);
        Route::patch('/update/{id}', [CourseController::class, 'update']);
        Route::get('{course_id}/participants', [CourseController::class, 'courseParticipants']);
        Route::get('/approval/list/', [CourseController::class, 'courseApprovalList']);
        Route::patch('/approval/{id}', [CourseController::class, 'courseApproval']);

        // tags
        Route::group(['prefix' => '/tags'], function () {
            Route::get('/list', [TagController::class, 'adminList']);
            Route::post('/create', [TagController::class, 'create']);
            Route::delete('/delete/{id}', [TagController::class, 'delete']);
            Route::patch('/update/{id}', [TagController::class, 'update']);
        });
    });

    // lectures
    Route::group(['prefix' => '/lecture'], function () {
        Route::post('/create', [LectureController::class, 'create']);
        Route::patch('/update/{id}', [LectureController::class, 'update']);
        Route::delete('/delete/{id}', [LectureController::class, 'delete']);
    });

    // fees
    Route::group(['prefix' => '/fees'], function () {
        Route::get('/', [FeeController::class, 'list']);
        Route::patch('/update/{id}', [FeeController::class, 'update']);
        Route::post('/create', [FeeController::class, 'create']);
        Route::delete('/delete/{id}', [FeeController::class, 'delete']);
    });

    // Account
    Route::group(['prefix' => '/account'], function () {
        Route::get('/', [AccountController::class, 'list']);
    });

    // Content
    Route::group(['prefix' => '/content'], function () {
        Route::get('/list', [ContentController::class, 'adminList']);
        Route::post('/create', [ContentController::class, 'create']);
        Route::patch('/update/{id}', [ContentController::class, 'update']);
        Route::delete('/delete/{id}', [ContentController::class, 'delete']);
    });
});
