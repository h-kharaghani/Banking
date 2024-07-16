<?php

namespace App\Libraries;

use App\Exceptions\BankException;
use App\Jobs\SmsJob;
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

    public static function convertArabicToEnglish($number): array|string
    {
        $newNumbers = range(0, 9);
        // 1. Persian HTML decimal
        $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
        // 2. Arabic HTML decimal
        $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
        // 3. Arabic Numeric
        $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        // 4. Persian Numeric
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

        $string = str_replace($persianDecimal, $newNumbers, $number);
        $string = str_replace($arabicDecimal, $newNumbers, $string);
        $string = str_replace($arabic, $newNumbers, $string);
        return str_replace($persian, $newNumbers, $string);
    }

    public static function sendSms($mobile, $text)
    {
        dispatch((new SmsJob($mobile, $text, $smsServiceProvider))->onQueue('banking-notification-queue'));
    }

    private static function getValidationMessage(string $messages): string
    {
        $message = '';
        foreach (json_decode($messages) as $item) {
            $message .= Arr::first($item) . "\n";
        }
        return $message;

    }

    public static function prepareSmsText(string $baseText, array $params): string
    {
        $message = '';
        foreach ($params as $key => $param) {
            if (!$message) {
                $message = str_replace("*{$key}*", $params['card_number'], $baseText);
            } else {
                $message = str_replace("*{$key}*", $params['amount'], $message);
                $message = str_replace("*{$key}*", $params['balance'], $message);

            }
        }
        return $message;
    }
}