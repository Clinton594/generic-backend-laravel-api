<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\MiniPaginate;
use App\Traits\Searchable;
use App\Traits\WithUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use  Searchable, Filterable, MiniPaginate, HasFactory, WithUser;

    protected $fillable = [
        'title', 'body', 'created_by', 'image', 'url', 'views', "type",  'status'
    ];

    protected $searchable = ["title", "body"];
    protected $filterable = ["status", "title"];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($content) {
            if (empty($content->image)) {
                $content->image = '/images/bullet-point-svgrepo-com.png';
            }
        });
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }
}
