<?php

namespace App\Http\Controllers;

use App\Casts\SmsText;
use App\Exceptions\BankException;
use App\Libraries\Helper;
use App\Models\Card;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class CardController extends Controller
{
    const validateMessages = []
    , validateAttributes = [
        'originCard' => 'شماره کارت مبدا',
        'destinationCard' => 'شماره کارت مقصد',
        'amount' => 'مبلغ',
    ];

    private function findCardByNumber(string $numbers): Card
    {
        $card = Card::where('number', $numbers)->first();
        throw_if(!$card, new BankException('card number not exists'));
        return $card;
    }

    /**
     * @param Request $request
     * @return void
     * @throws BankException
     */
    private function cardToCardValidation(Request $request): void
    {
        Helper::makeValidate($request->all(), [
            'originCard' => 'bail|required|string|size:16|persian_number|ir_card_number',
            'destinationCard' => 'bail|required|string|size:16|persian_number|ir_card_number',
            'amount' => 'bail|required|numeric|min:10000|max:500000000',
        ], self::validateMessages, self::validateAttributes);
    }

    /**
     * @param Card $originCardInfo
     * @param mixed $amount
     * @param Card $destinationCardInfo
     * @return void
     * @throws BankException
     */
    private function startToCardToCardTransfer(Card &$originCardInfo, mixed $amount, Card &$destinationCardInfo): void
    {
        DB::beginTransaction();
        try {
            $originCardInfo->balance = $originCardInfo->balance - $amount;
            $originCardInfo->save();
            $transaction = $originCardInfo->transactions()->create([
                'amount' => $amount,
                'description' => 'card to card',
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
    }

    /**
     * @param Card $originCardInfo
     * @param mixed $amount
     * @param $originCardOwner
     * @param Card $destinationCardInfo
     * @param $destinationCardOwner
     * @return void
     */
    private function notify(Card $originCardInfo, mixed $amount, $originCardOwner, Card $destinationCardInfo, $destinationCardOwner): void
    {
        $params = [
            'card_number' => $originCardInfo->number,
            'amount' => $amount,
            'balance' => $originCardInfo->balance,
        ];
        $origMsg = Helper::prepareSmsText(SmsText::decreaseـaccountـbalance, $params);
        Helper::sendSms($originCardOwner->mobile, $origMsg);

        $params = [
            'card_number' => $destinationCardInfo->number,
            'amount' => $amount,
            'balance' => $destinationCardInfo->balance,
        ];
        $destMsg = Helper::prepareSmsText(SmsText::increaseـaccountـbalance, $params);
        Helper::sendSms($destinationCardOwner->mobile, $destMsg);
    }

    /**
     * @throws BankException
     */
    public function cardToCardTransfer(Request $request)
    {
        $this->cardToCardValidation($request);
        $amount = $request->amount;
        $origin = Helper::convertArabicToEnglish($request->originCard);
        $destination = Helper::convertArabicToEnglish($request->destinationCard);
        throw_if($origin == $destination, new BankException('bothCardsAreSame'));

        $originCardInfo = $this->findCardByNumber($origin);
        $destinationCardInfo = $this->findCardByNumber($destination);

        $originCardOwner = auth()->user();
        $destinationCardOwner = $destinationCardInfo->account->user;

        throw_if($originCardInfo->balance <= ($amount + Transaction::prices[Transaction::card_to_card]), new BankException('amount is more than balance'));
        throw_if($originCardInfo->account->user->mobile != auth()->user()->mobile, new BankException('this is not for this user'));
        throw_if(!$destinationCardOwner, new BankException('user not found'));

        $this->startToCardToCardTransfer($originCardInfo, $amount, $destinationCardInfo);
        $this->notify($originCardInfo, $amount, $originCardOwner, $destinationCardInfo, $destinationCardOwner);

        return $this->jsonSuccessResponse();
    }

    public function getTransactions()
    {
        $tenMinutesAgo = now()->subMinutes(10);

        // Step 1: Find the user with the most transactions in the last 10 minutes
        $topUsersTransactions = Transaction::query()
            ->join('cards', 'transactions.card_id', '=', 'cards.id')
            ->join('accounts', 'cards.account_id', '=', 'accounts.id')
            ->join('users', 'accounts.user_id', '=', 'users.id')
            ->where('transactions.created_at', '>=', $tenMinutesAgo)
            ->select('users.id', DB::raw('COUNT(transactions.id) as transaction_count'))
            ->groupBy('users.id')
            ->orderBy('transaction_count', 'desc')
            ->limit(3)->get();
        $userIds = array_column($topUsersTransactions->toArray(), 'id');
        if (!empty($topUsersTransactions)) {
            $data = User::query()->whereIn('id', $userIds)->with(['transactions' => function (Builder $query) use ($tenMinutesAgo) {
                $query->where('transactions.created_at', '>=', $tenMinutesAgo)->limit(10);
            }])->get()->map(function ($item) use ($topUsersTransactions) {
                $item->transactions_count = collect($topUsersTransactions)->where('id', $item->id)->first()->transaction_count;
                return $item;
            })->toArray();
        } else {
            $data = [];
        }

        return $this->jsonSuccessResponse($data);
    }
}
