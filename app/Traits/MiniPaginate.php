<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait MiniPaginate
{
  private $miniPaginateBuilder;

  private function bindRequest($miniPaginateBuilder, $request)
  {
    if ($request) $this->request = $request;
    $limit = ($this->request->limit) ?? config('data.paginate10');
    if (empty($this->builder)) $this->builder = $miniPaginateBuilder->paginate($limit, ['*'], 'page');
    return $this->builder;
  }

  function scopeMiniPaginate(Builder $miniPaginateBuilder, Request $request)
  {
    $resourse = $this->bindRequest($miniPaginateBuilder, $request);

    return [
      "prev" => $resourse->currentPage() > 1 ? true : false,
      "page" => $resourse->currentPage(),
      "records" => $resourse->total(),
      "pages" => $resourse->lastPage(),
      "next" => $resourse->hasMorePages()
    ];
  }

  function scopeGetList(Builder $miniPaginateBuilder)
  {
    $resourse = $this->bindRequest($miniPaginateBuilder, null);
    return $resourse->items();
  }
}
