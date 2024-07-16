<?php

namespace App\Http\Controllers;

use App\Casts\SmsText;
use App\Exceptions\BankException;
use App\Libraries\Helper;
use App\Models\Card;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\TextUI\Help;
use Throwable;

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
        $amount = $request->amount;
        $origin = Helper::convertArabicToEnglish($request->originCard);
        $destination = Helper::convertArabicToEnglish($request->destinationCard);
        throw_if($origin == $destination, new BankException('bothCardsAreSame'));

        $originCardInfo = $this->findCardByNumber($origin);
        $destinationCardInfo = $this->findCardByNumber($destination);

        $originCardOwner = auth()->user();
        $destinationCardOwner = $destinationCardInfo->account->user;

        throw_if($originCardInfo->balance <= $amount, new BankException('amount is more than balance'));
        throw_if($originCardInfo->account->user->mobile != auth()->user()->mobile, new BankException('account info is wrong'));
        throw_if(!$destinationCardOwner, new BankException('user not found'));

        DB::beginTransaction();
        try {
            $originCardInfo->balance = $originCardInfo->balance - $amount;
            $originCardInfo->save();
            $transaction = $originCardInfo->transactions()->create([
                'destination_card_id' => $destinationCardInfo->id,
                'amount' => $amount,
                'description' => 'card to card', //todo
                'type' => Transaction::card_to_card,
            ]);
            $transaction->fee()->create([
                'amount' => Transaction::prices[Transaction::card_to_card],
            ]);
            $destinationCardInfo->balance = $destinationCardInfo->balance + $amount;
            $destinationCardInfo->save();
            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();
            throw new BankException('card to card failed', 0, $exception);
        }
        $params = [
            'card_number' => $originCardInfo->number,
            'amount' => $amount,
            'balance' => $originCardInfo->balance,
        ];
        $this->notify($originCardOwner->mobile, SmsText::decreaseـaccountـbalance, $params);
        $params = [
            'card_number' => $destinationCardInfo->number,
            'amount' => $amount,
            'balance' => $destinationCardInfo->balance,
        ];
        $this->notify($destinationCardOwner->mobile, SmsText::increaseـaccountـbalance, $params);

        return $this->jsonSuccessResponse();
    }

    private function findCardByNumber(string $numbers): Card
    {
        $card = Card::where('number', $numbers)->first();
        throw_if(!$card, new BankException('card number not exists'));
        return $card;
    }

    private function notify($mobile, string $baseText, array $params): void
    {
        $message = Helper::prepareSmsText($baseText, $params);
        Helper::sendSms($mobile, $message);
    }
}
