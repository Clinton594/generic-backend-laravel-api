<?php

namespace App\Services\Content;

use App\Helpers\Response;
use App\Models\Content;
use App\Services\Image\ImageManagementService;
use Illuminate\Support\Facades\Auth;

class CreateContentService
{
  static function handle($request)
  {
    $Response = new Response;
    $response = $Response::get();

    try {

      $user = Auth::user();

      // Extract param fields
      $fields = $request->only(["type", "title", "body", "image", "status", 'url']);

      // attach the user id of the admin
      $fields["created_by"] = $user->id;

      // attach a slug
      if (empty($fields['url'])) {
        $fields["url"] = slugify($fields["title"]);
      }
      // handle image
      if (!empty($fields['image'])) {
        $fields['image'] = ImageManagementService::processImageField($request, null, 'content', 'image');
      }

      // create the content
      $content = Content::create($fields);

      // log is handled by Content observer

      $response = $response = $Response::set(["data" => $content], true);
    } catch (\Throwable $th) {
      $response = $Response::set(["message" => $th->getMessage(), "code" => getExceptionCode($th)]);
    }

    return $response;
  }
}
