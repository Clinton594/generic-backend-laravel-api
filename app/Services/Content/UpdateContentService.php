<?php

namespace App\Services\Content;

use App\Helpers\Response;
use App\Models\Content;
use App\Services\Image\ImageManagementService;

class UpdateContentService
{
  static function handle($request)
  {
    $Response = new Response;
    $response = $Response::get();

    try {

      // Fetch content from database
      $content = Content::findOrFail($request->id);

      // Extract param fields
      $fields = $request->only(["title", "body", "image", "status", "url"]);

      // handle image
      if (!empty($fields['image'])) {
        $fields['image'] = ImageManagementService::processImageField($request, null, 'content', 'image');
      }

      // update content to database
      $content->update($fields);

      // log is handled by ContentObserver

      $response = $Response::set(["data" => $content->refresh()], true);
    } catch (\Throwable $th) {
      $response = $Response::set(["message" => $th->getMessage(), "code" => getExceptionCode($th)]);
    }

    return $response;
  }
}
