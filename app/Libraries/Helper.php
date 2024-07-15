<?php

namespace App\Libraries;

use App\Exceptions\BankException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class Helper
{
    /**
     * @throws BankException
     */
    public static function makeValidate(array $data, array $rules, array $customMessages = [], array $customAttr = [])
    {
        $validator = Validator::make($data, $rules, $customMessages, $customAttr);
        if ($validator->fails()) {
            $message = self::getValidationMessage($validator->messages());
            throw new BankException($message);
        }
    }

    public static function jalaliToGregorianNumber(string $number)
    {

    }

    private static function getValidationMessage(string $messages): string
    {
        $message = '';
        foreach (json_decode($messages) as $item) {
            $message .= Arr::first($item) . "\n";
        }
        return $message;

    }
}