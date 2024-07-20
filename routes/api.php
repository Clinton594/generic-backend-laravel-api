<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return app()->version();
});


// Route::get('/welcome-email', function () use ($router) {
//     return new WelcomeMail([
//         "title" => "Welcome to Codelandcs",
//         "body" => "Your account registration was successful. We are happy to recieve you, Clinton",
//         "otp" => rand(1000, 9999)
//     ]);
// });



// Students Route
require_once "api/users/index.php";

// Admin Route
Route::prefix('/admin')->group(base_path('routes/api/admin/index.php'));
