<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\MiniPaginate;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Notification extends Model
{
    use  Searchable, MiniPaginate, Filterable;

    protected $filterable = ["viewed", "user_id"];

    protected $searchable = ["title", "message"];

    protected $fillable = [
        'user_id', 'title', "message"
    ];
}
