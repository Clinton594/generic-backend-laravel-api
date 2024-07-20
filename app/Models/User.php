<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Listeners\RegistrationListener;
use App\Traits\Filterable;
use App\Traits\MiniPaginate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use  Authorizable, HasFactory,  Notifiable, HasApiTokens, Filterable, MiniPaginate;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name', 'last_name', 'phone', 'image', 'country', 'gender',
    ];

    public $filterable = ['first_name', 'last_name', 'phone', 'email', 'type', 'status'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password', 'updated_at', 'role', 'access_level', 'google_auth'
    ];

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            $user->first_name =  ucfirst(strtolower($user->first_name));
            $user->last_name =  ucfirst(strtolower($user->last_name));
            $user->email =  strtolower($user->email);
            $user->user_name =  strtolower($user->user_name);
            if (empty($user->image)) {
                $user->image = '/images/user/default.jpg';
            }
            return $user;
        });
    }



    public function transaction()
    {
        return $this->hasMany('App\Models\Transaction');
    }

    public function contents()
    {
        return $this->hasMany('App\Models\Content', 'created_by');
    }

    public static function concactName()
    {
        return "(CONCAT( users.first_name, ' ', users.last_name)) AS full_name";
    }

    public static function getFullName($user)
    {
        $user->full_name = ucwords(strtolower("{$user->first_name} {$user->last_name}"));
        return $user;
    }

    public function scopeIsActive(Builder $query, bool $isactive = false): void
    {
        $query->when($isactive && $query->count(), fn ($query) => $query->where('status', config('data.userStatus.active')));
    }
}
