<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait WithUser
{
  public function scopeWithUser(Builder $builder)
  {
    $builder->with(["user" => function ($query) {
      $query->select("id", "first_name", "last_name", "image");
    }]);
  }
}
