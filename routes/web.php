<?php

use App\Models\Company;
use App\Models\User;
use App\Services\Notify\MailService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Emails
Route::group(['prefix' => '/mail'], base_path('/routes/web/emails/index.php'));

// PDF
Route::group(['prefix' => '/pdf'], base_path('/routes/web/pdf/index.php'));
