<?php

namespace App\Services\Content;

use App\Helpers\Response;
use App\Models\Content;
use App\Models\Reviews;

class ContentListService
{

  function list($request): object
  {
    $Response = new Response();
    $response = $Response::get();

    try {
      $default = ['title', 'body', 'url as slug', 'updated_at'];
      $testimony = ['title as name', 'body as comment', 'image'];

      $type = strtoupper(basename($request->url()));
      $reviews_count = 0;
      $reviews = $contents = [];

      // Get user uploaded testimonials
      if ($type === "TESTIMONIAL") {
        $student_reviews = Reviews
          ::where("rate", ">", "3")
          ->withUser()
          ->inRandomOrder()
          ->limit(5)
          ->get();

        foreach ($student_reviews as $content) {
          $reviews[] = [
            "comment" => $content->comment,
            "name" => "{$content->user->first_name} {$content->user->last_name}",
            "image" => asset(remove_thumbnail($content->user->image))
          ];
        }
        $reviews_count = $student_reviews->count();
      }

      // Get other content types not testimony, or testimonies if user uploaded testimonials does not exist
      if ($type !== "TESTIMONIAL" || ($type === "TESTIMONIAL" && $reviews_count < 3)) {
        $data  = Content::where([
          ['type', $type],
          ['status', config('data.approval.approved')]
        ])
          ->oldest()
          ->get($type === 'TESTIMONIAL' ? $testimony :  $default);

        // Format the content
        foreach ($data as $content) {
          $c = [...$content->toArray()];
          if (!empty($content->image)) $c["image"] = asset(remove_thumbnail($content->image));
          if (!empty($content->body)) $c["body"] = cleanupHTML($content->body);
          $contents[] = $c;
        }
      }

      // Merge student reviews if any into testimonials \ contents
      $data = array_merge($reviews, $contents);

      $response =  $Response::set([
        "data" => $data
      ], true);
    } catch (\Throwable $th) {
      $response =  $Response::set(["message" => $th->getMessage(), 'code' => getExceptionCode($th)]);
    }

    return $response;
  }

  // list for admin endpoint
  function adminList($request): object
  {
    $Response = new Response();
    $response = $Response::get();

    try {

      // get specifi content
      $data  = Content::filter($request)->where([
        ['type', $request->type]
      ])->withUser()->oldest();

      // format response
      $response =  $Response::set([
        "data" => [
          "pagination" => $data->miniPaginate($request),
          "list" => array_map(fn ($content) => [...$content->toArray(), "image" => asset($content->image)], $data->getList()),
        ],
      ], true);
    } catch (\Throwable $th) {
      $response =  $Response::set(["message" => $th->getMessage(), 'code' => getExceptionCode($th)]);
    }

    return $response;
  }
}
