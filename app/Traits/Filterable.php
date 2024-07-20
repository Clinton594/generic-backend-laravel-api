<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Filterable
{

  private $actions = ['where', "like", 'dateBetween'];

  /**
   * add filtering.
   *
   * @param  $builder: query builder.
   * @param  $filters: array of filters.
   * @return query builder.
   */
  public function scopeFilter(Builder $builder, Request $request)
  {
    // {"where":{"first_name":"John", "last_name":"Emma"}, }
    // {"like":{"first_name":"John", "last_name":"Emma"}, "dateBetween":{"created_at":["2024-01-01", "2024-01-01"]}}
    // Return builder when the request has nothing to filter
    if (!$request->has('filters')) return $builder;

    // Table Name
    $tableName = $this->getTable();

    // Filters Object
    $filters = isJson($request->input("filters")) ?? [];


    $filterable = ['created_at'];
    $filterable =  array_unique(array_merge($filterable, $this->filterable ?? []));

    // Loop through all actions of the filter
    foreach ($filters as $clause => $values) {

      if (in_array($clause, $this->actions)) {

        // Per action
        foreach ($values as $field => $value) {

          // feild is filterable
          if (in_array($field, $filterable)) {

            // Where clause
            if ($clause === "where") {
              // Boolean search
              if (isset($this->boolFilterFields) && in_array($field, $this->boolFilterFields) && $value != null) {
                $builder->where($field, (bool)$value);
                continue;
              } elseif (is_array($value)) {
                $builder->whereIn($field, $value);
              } else $builder->where($field, $value);
            }
            // Like clause
            else if ($clause === 'like') {
              $builder->where($tableName . '.' . $field, 'LIKE', "%$value%");
            }
            // Date Range clause
            else {
              foreach ($request as $key => $value) {
                $start = $value[0];
                $end = $value[1];
                if ($value[0] == "" or $value[0] == null) {
                  $start = Carbon::now();
                }
                if ($value[1] == "" or $value[1] == null) {
                  $end = Carbon::now();
                }
                if ($start != null && $end != "") {
                  $builder->whereBetween($key, [$start, $end]);
                }
              }
            }
          }
        }
      }
    }
    return $builder;
  }
}
