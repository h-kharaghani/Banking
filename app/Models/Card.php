<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'number',
        'state',
        'type',
        'issue_date',
        'end_date',
    ];

    public const
        state_registered = 10
    , state_activated = 20
    , state_expired = 30
    , gift = 10
    , normal = 20;

    public function getUserInfo()
    {
        return $this->morphTo();
    }
}
