<?php

namespace App\Services\Notify;

use App\Jobs\MailQueuer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailService
{

  static function send(string $to, $data, $queue = true, array $cc = []): object
  {
    try {

      // Record the email in the table
      // Ask charles / mark how they do this

      $queue ? MailQueuer::dispatch($to, $data, $cc) : Mail::to($to)->cc($cc)->send($data);

      return object(["status" => true, "message" => "Email Sent", "code" => 200]);
    } catch (\Throwable $th) {
      return object(["status" => false, "message" => $th->getMessage(), "code" => 400]);
    }
  }
}
