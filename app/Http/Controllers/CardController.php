<?php

namespace App\Http\Controllers;

use App\Exceptions\BankException;
use App\Libraries\Helper;
use App\Models\Card;
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
            'originCard' => 'bail|required|string|size:16|persian_number|ir_card_number',
            'destinationCard' => 'bail|required|string|size:16|persian_number|ir_card_number',
            'amount' => 'bail|required|numeric|min:10000|max:500000000',
        ], self::validateMessages, self::validateAttributes);
        $origin = Helper::convertArabicToEnglish($request->originCard);
        $destination = Helper::convertArabicToEnglish($request->destinationCard);
        throw_if($origin == $destination, new BankException('bothCardsAreSame'));
        $originInfo = $this->findCardByNumber($origin);
        $destinationInfo = $this->findCardByNumber($destination);
        $cardUser = $originInfo->getUserInfo();

    }

    private function findCardByNumber(string $numbers): Card
    {
        $card = Card::where('number', $numbers)->first();
        throw_if(!$card, new BankException('card number not exists'));
        return $card;
    }
}
