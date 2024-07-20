<?php

namespace App\Services\Notify;

use App\Helpers\Response;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Rap2hpoutre\LaravelLogViewer\LaravelLogViewer;

class Logger
{
  /**
   * @param $model
   * @return void
   */
  static function created(Model $model)
  {
    $ip = Request::getClientIp();
    $userAgent  = Request::userAgent();
    $modeldir   = get_class($model);
    $model_name = class_basename($model);

    // custom activity actions from model name
    $actions = self::messages($model);
    // Get user
    $user   =  Auth::user() ?? $actions["user"];

    $action = $actions['created'] ?? "{$model_name} Creation";
    $action = str_replace("{user}", "{$user->first_name} {$user->last_name}", $action);
    $action = str_replace("{agent}", $userAgent, $action);
    $action = str_replace("{ip}", $ip, $action);

    $description = $actions['created_desc'] ?? "{$user->first_name} {$user->last_name} just created a new {$model_name}";

    $activity = new Activity;
    $activity->user_id = $user->id;
    $activity->table_name = $modeldir;
    $activity->table_id   = $model->getKey();
    $activity->action = $action;
    $activity->description = $description;

    $activity->save();
  }

  // auto generates log from the columns changed in the model
  static function updated(Model $model)
  {
    $ip = Request::getClientIp();
    $userAgent  = Request::userAgent();
    $modeldir   = get_class($model);
    $model_name = class_basename($model);

    // custom activity actions from model name
    $actions = self::messages($model);
    // Get user
    $user   =  Auth::user() ?? $actions["user"];

    // get changes
    $changes    = array_slice($model->getDirty(), 0, -1);
    $prev_model = $model->getOriginal();

    // getting the first changed attribute
    $changed_column = array_key_first($changes);
    // get the changed value
    $new_value = $changes[$changed_column];
    // how man columns where changed
    $changed_count = count($changes);
    // initial value
    $initial_value = $prev_model[$changed_column];

    $action = "{$model_name} Update";

    // see($changes);

    if ($changed_count > 1)
      $description = "{$user->first_name} {$user->last_name} has updated {$model_name} {$changed_column}. and {$changed_count} other values.";
    else {
      $aliases = $actions["updated"] ?? [];
      $description = $aliases["{$changed_column}.message"] ?? "{$user->first_name} {$user->last_name} changed {$model_name} {$changed_column} from {$initial_value} to {$new_value}.";
      if (isset($aliases["{$changed_column}"])) {
        $description = str_replace($changed_column, $aliases[$changed_column], $description);
      }
      $description = str_replace("{user}", "{$user->first_name} {$user->last_name}", $description);
      $description = str_replace("{agent}", $userAgent, $description);
      $description = str_replace("{ip}", $ip, $description);
    }

    $activity = new Activity;
    $activity->user_id = $user->id;
    $activity->table_name = $modeldir;
    $activity->table_id   = $model->getKey();
    $activity->action = $action;
    $activity->description = $description;

    $activity->save();
  }

  static function deleted(Model $model)
  {
    $modeldir   = get_class($model);
    $model_name = class_basename($model);

    // custom activity actions from model name
    $actions = self::messages($model);
    // Get user
    $user   =  Auth::user() ?? $actions["user"];

    $action =  "{$model_name} Deletion";
    $description = "{$user->first_name} {$user->last_name} just deleted a {$model_name} ($model->id)";

    $activity = new Activity;
    $activity->user_id = $user->id;
    $activity->table_name = $modeldir;
    $activity->table_id   = $model->getKey();
    $activity->action = $action;
    $activity->description = $description;

    $activity->save();
  }

  static function error($request): object
  {
    // Initialize response class
    $Response = new Response;
    $response = $Response::get();

    // Get request parameters for pagination
    $page = (int) $request->input('page') ?: 1;
    $limit = (int) $request->input('limit') ?: 10;

    try {
      $logger  = new LaravelLogViewer;

      // Get all error logs
      $data = $logger->all();

      // Extract the only the keys that i need
      $data = array_map(fn ($error) => array_extract($error, ['level',  'level_class', 'level_img', 'date', 'text'], false), $data);

      // Filter by error type
      if ($request->level) {
        $data = array_filter($data, fn ($error) => $error['level'] === $request->level);
      }

      // Search the error
      if ($request->search) {
        $data = array_filter($data, fn ($error) => stripos($error['text'], $request->search) > -1);
      }

      // reindex the array keys after filtering
      $data = array_values($data);

      // Build pagination data on it
      $files = collect($data);
      $slice = $files->slice(($page - 1) * $limit, $limit);
      $paginator = new \Illuminate\Pagination\LengthAwarePaginator($slice, $files->count(), $limit);

      // Build response
      $response = $Response::set(['data' => $paginator], true);
      //code...
    } catch (\Throwable $th) {
      $response = $Response::set(['message' => $th->getMessage(), 'code' => getExceptionCode($th)]);
    }
    return $response;
  }

  private static function messages(Model $model): array
  {
    $model_name = class_basename($model);

    return [
      "User" => [
        "created" => "User Registration",
        "created_desc" => "{user} just created an account from {agent}-({ip})",
        "user" => $model,
        "updated" => [
          "remember_token.message" => "{user} just signed in from {agent}-{ip}, your login session has been updated",
          "email_verified_at.message" => "{user} has verified your email",
          "password.message" => "{user} has changed your password from {agent}-{ip}",
          "image.message" => "{user} has update your profile picture",
        ]
      ],
      "TokenVerification" => [
        "created" => ucwords(toSentence("{$model->tokenFor} token")),
        "created_desc" => "{user} generated a new token for ",
        "user" => $model->user,
        "updated" => []
      ]
    ][$model_name] ?? [];
  }
}
