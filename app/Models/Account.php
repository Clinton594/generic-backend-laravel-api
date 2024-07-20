<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use  HasFactory;

    protected $fillable = [
        'user_id', "over_draft", 'balance', 'account_number', "account_name", 'bank_code', "status"
    ];
}
