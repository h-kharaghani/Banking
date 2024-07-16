<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin_card_id',
        'destination_card_id',
        'amount',
        'description',
        'type',
    ];

    /** transactions types */
    public const
        card_to_card = 10,
        buy_mobile_charge = 20,
        prices = [
        self::card_to_card => 5000,
        self::buy_mobile_charge => 2000,
    ];


    public function fee(): HasOne
    {
        return $this->hasOne(TransactionFee::class);
    }
}
