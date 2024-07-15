<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin_card_id',
        'destination_card_id',
        'amount',
        'description',
        'type',
        'transaction_date',
    ];
}
