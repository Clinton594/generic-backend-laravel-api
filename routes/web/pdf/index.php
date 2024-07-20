<?php

use App\Models\Company;
use App\Models\Course;
use App\Models\User;
use App\Services\Notify\MailService;
use Barryvdh\DomPDF\Facade\Pdf;
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
// Route::group(['prefix' => '/certificate'], function () {

//   $company = Company::get();
//   // see(config("mail"));
//   $user = User::where('status', 'ACTIVE')->inRandomOrder()->first();
//   $course = Course::where('status', 'ACTIVE')->inRandomOrder()->first();
//   $pdf = Pdf::loadView('pdf.certificate', ["certificate" => $course]);

//   $message =
//     array_merge(
//       [
//         "subject" => "Certificate of Completion in {$course->name}",
//         "body" => "We are pleased to inform you that you have successfully completed the {$course->name} course. Congratulations on this achievement",
//         "course_name" => $course->name
//         // "pdf" => $pdf,
//       ],
//       [
//         "to_name" => "{$user->first_name} {$user->last_name}",
//         "company" => $company
//       ]
//     );
//   Route::get('/', function () use ($message) {

//     // dd($message);
//     return new App\Mail\CertificateMail($message);
//   });

//   Route::get('/send', function () use ($message) {
//     $response =  MailService::send('albertonyishi1@gmail.com', new App\Mail\CertificateMail($message));
//     return response()->json($response);
//   });
// });
