<?php

namespace App\Http\Controllers;

use App\Exceptions\BankException;
use App\Libraries\Helper;
use Illuminate\Http\Request;

class CardController extends Controller
{
    const validateMessages = []
    , validateAttributes = [
        'originCard' => 'شماره کارت مبدا',
        'destinationCard' => 'شماره کارت مقصد',
        'amount' => 'مبلغ',
    ];

    /**
     * @throws BankException
     */
    public function transfer(Request $request)
    {
        Helper::makeValidate($request->all(), [
            'originCard' => 'bail|required|digits:16|ir_card_number',
            'destinationCard' => 'bail|required|digits:16|ir_card_number',
            'amount' => 'bail|required|numeric|min:10000|max:500000000',
        ], self::validateMessages, self::validateAttributes);
        $this->getUserInfoByCardNum($request->originCard);
        $this->getUserInfoByCardNum($request->destinationCard);
        //get account info by card number
        //get account balance
        //
    }

    private function getUserInfoByCardNum(mixed $originCard)
    {
        
    }
}
