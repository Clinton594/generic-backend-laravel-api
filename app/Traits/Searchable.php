<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

// To search any model
trait Searchable
{
  /**
   * add filtering.
   * @param  $builder: query builder.
   * @param  $search: string to seach.
   * @return query builder.
   */
  public function scopeSearch(Builder $builder, Request $request)
  {

    if (!$request->has('search')) return $builder;

    // Search Param
    $search = $request->input("search");

    // fields to search
    $searchable =  $this->searchable ?? [];

    // Loop through all fields and build search
    foreach ($searchable as $field) {
      $builder->orWhere($field, 'LIKE', '%' . $search . '%');
    }

    return $builder;
  }
}
