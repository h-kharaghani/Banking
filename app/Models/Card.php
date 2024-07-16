<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'number',
        'state',
        'type',
        'balance',
        'issue_date',
        'end_date',
    ];

    public const
        state_registered = 10
    , state_activated = 20
    , state_expired = 30
    , gift = 10
    , normal = 20;

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class , 'origin_card_id');
    }
}
