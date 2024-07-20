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


// Welcome Mail
Route::group(['prefix' => '/welcome'], function () {

  $company = Company::get();
  // see(config("mail"));
  $user = User::where('status', 'ACTIVE')->inRandomOrder()->first();
  $message =
    array_merge(
      [

        "subject" => "Welcome to {$company->name}",
        "body" => "Your account registration was successful. We are happy to recieve you, {$user->first_name}",
        "otp" => '89800'
      ],
      [
        "to_name" => "{$user->first_name} {$user->last_name}",
        "company" => $company
      ]
    );
  Route::get('/', function () use ($message) {
    return new App\Mail\WelcomeMail($message);
  });

  Route::get('/send', function () use ($message) {
    $response =  MailService::send('albertonyishi1@gmail.com', new App\Mail\WelcomeMail($message));
    return response()->json($response);
  });
});

// Verifcation Approval
Route::group(['prefix' => '/verified'], function () {

  $company = Company::get();
  $user = User::where('status', 'ACTIVE')->inRandomOrder()->first();
  $message =
    array_merge(
      [
        "subject" => "Welcome to {$company->name}",
        "body" => "Your account registration was successful. We are happy to recieve you, {$user->first_name}",
      ],
      [
        "to_name" => "{$user->first_name} {$user->last_name}",
        "company" => $company
      ]
    );
  Route::get('/', function () use ($message) {
    return new App\Mail\EmailVerified($message);
  });

  Route::get('/send', function () use ($message) {
    $response =  MailService::send('ucmodulus@gmail.com', new App\Mail\EmailVerified($message));
    return response()->json($response);
  });
});


//OTP Code
Route::group(['prefix' => '/token'], function () {

  $company = Company::get();
  $user = User::where('status', 'ACTIVE')->inRandomOrder()->first();
  $message =
    array_merge(
      [
        "subject" => "Email Verification",
        // "otp" => '9a7222a0-1b62-4ff3-aeb7-08249caf1683',
        "otp" => '453545'
      ],
      [
        "to_name" => "{$user->first_name} {$user->last_name}",
        "company" => $company
      ]
    );
  Route::get('/', function () use ($message) {
    return new App\Mail\OTPTokenMail($message);
  });

  Route::get('/send', function () use ($message) {
    $response =  MailService::send('ucmodulus@gmail.com', new App\Mail\OTPTokenMail($message));
    return response()->json($response);
  });
});


// Password Reset
Route::group(['prefix' => '/password-reset'], function () {

  $company = Company::get();
  $user = User::where('status', 'ACTIVE')->inRandomOrder()->first();
  $message =
    array_merge(
      [
        "to_name" => "{$user->first_name} {$user->last_name}",
        "subject" => "Password Reset",
      ],
      [
        "to_name" => "{$user->first_name} {$user->last_name}",
        "company" => $company
      ]
    );
  Route::get('/', function () use ($message) {
    return new App\Mail\PasswordResetMail($message);
  });

  Route::get('/send', function () use ($message) {
    $response =  MailService::send('ucmodulus@gmail.com', new App\Mail\PasswordResetMail($message));
    return response()->json($response);
  });
});



// lecture template
Route::group(['prefix' => '/lecture'], function () {

  $company = Company::get();
  $user = User::where('status', 'ACTIVE')->inRandomOrder()->first();

  $message =
    array_merge(
      [
        "course_image" => "https://dev.codelandcs.com/storage/images/course/devops-ci-cd-pipeline-with-jenkins-and-docker-1-0ff7.jpeg",

        "course_name" => "Devops: ci/cd pipeline with jenkins and docker",
        "subject" => "Password Reset",
        "lecture_topic" => "Devops: ci/cd pipeline with jenkins and docker",
        "lecture_date" => "2024-07-02",
        "lecture_time" => "08:09:05",
        "lecture_link" => "http://codelandcs/course/devops-ci-cd-pipeline-with-jenkins-and-docker-1-0ff7"
      ],
      [
        "to_name" => "{$user->first_name} {$user->last_name}",
        "company" => $company
      ]
    );
  Route::get('/', function () use ($message) {
    return new App\Mail\LectureMail($message);
  });

  Route::get('/send', function () use ($message) {
    $response =  MailService::send('ucmodulus@gmail.com', new App\Mail\LectureMail($message));
    return response()->json($response);
  });
});
