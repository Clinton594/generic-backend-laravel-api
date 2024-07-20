<?php

namespace App\Services\Content;

use App\Helpers\Response;
use App\Models\Content;

class DeleteContentService
{
  static function handle($reqeust)
  {
    $Response = new Response;
    $response = $Response::get();

    try {

      // Find the content by id
      $content = Content::findOrFail($reqeust->id);

      // Delete the content
      $content->delete();

      // log is handled by Content observer

      $response = $Response::set(["message" => "Successfuly deleted"], true);
    } catch (\Throwable $th) {
      $response = $Response::set(["message" => $th->getMessage(), "code" => getExceptionCode($th)]);
    }

    return $response;
  }
}
