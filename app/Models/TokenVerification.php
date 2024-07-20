<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;


class TokenVerification extends Model
{

    use HasUuids;

    protected $fillable = [
        'user_id', 'tokenFor', "OTP", 'is_used'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function (Model $token) {
            $time = config('data.tokenExpiration');
            $token->expires_at = new DateTime("+{$time}min");
        });
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
