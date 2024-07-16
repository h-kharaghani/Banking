<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class SmsText
{
    public const
        increaseـaccountـbalance =
        "\n"
        . "واریز به کارت: *card_number* مبلغ: *amount* "
        . "\n"
        . "مانده موجودی: *balance*"
        . "\n"
    , decreaseـaccountـbalance =
        "\n"
        . "برداشت از کارت: *card_number* مبلغ: *amount* "
        . "\n"
        . "مانده موجودی: *balance*"
        . "\n";
}
