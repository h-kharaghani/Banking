<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bank_id',
        'type',
        'state',
        'number',
        'sheba',
        'balance',
        'end_date',
    ];
    public const currency_account_type = 100
    , saving_account_type = 200
    , state_registered = 10
    , state_activated = 20
    , state_deactivated = 30;
}
