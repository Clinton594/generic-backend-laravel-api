<?php

namespace App\Services\Notify;

use App\Helpers\Response;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
  static function notificationList($request): object
  {
    $Response = new Response;
    $response = $Response::get();

    try {
      // Get logged in user
      $user = Auth::user();

      // Get a builder of all notifications of the user and make it filterable for admin usage
      $builder = Notification::filter($request)->where('user_id', $user->id)->latest();

      // Get the unread count from the builder
      $unread = $builder->where('viewed', config('data.boolean.false'))->count();

      // Get pagination from the builder
      $pagination = $builder->miniPaginate($request);

      // Build the list per page
      $list  = collect($builder->getList())->map(fn ($n) => [
        'id' => $n->id,
        'title' => $n->title,
        'preview' => first_sentence($n->message),
        'fullMessage' => $n->message,
        'viewed' => json_decode(strtolower($n->viewed)),
        'time' => Carbon::parse($n->created_at)->diffForHumans(),
      ]);

      // return response
      $response = $Response::set(['data' => ['unread' => $unread, 'pagination' => $pagination, 'list' => $list]], true);
      //code...
    } catch (\Throwable $th) {
      $response = $Response::set(['message' => $th->getMessage(), 'code' => getExceptionCode($th)]);
    }
    return $response;
  }

  static function markAll($request): object
  {
    $Response = new Response;
    $response = $Response::get();

    try {
      $user = Auth::user();

      Notification::where('user_id', $user->id)->update(['viewed' => config('data.boolean.true')]);

      return self::notificationList($request);
    } catch (\Throwable $th) {
      $response = $Response::set(['message' => $th->getMessage(), 'code' => getExceptionCode($th)]);
    }
    return $response;
  }

  static function markOne($request): object
  {
    $Response = new Response;
    $response = $Response::get();

    try {
      $notification = Notification::findOrFail($request->id);
      $notification->viewed = config('data.boolean.true');
      $notification->save();

      return self::notificationList($request);
    } catch (\Throwable $th) {
      $response = $Response::set(['message' => $th->getMessage(), 'code' => getExceptionCode($th)]);
    }
    return $response;
  }
}
