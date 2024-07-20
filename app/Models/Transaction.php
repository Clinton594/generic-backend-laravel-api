<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\MiniPaginate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Transaction extends Model
{
    use  MiniPaginate, Filterable;

    protected $fillable = [
        'reference', "user_id", "amount", "description", "tx_type", "status",
    ];


    public function scopeRecent(Builder $query): Builder
    {
        return $query->where('created_at', '>=', Carbon::now()->subHours(24));
    }
}
