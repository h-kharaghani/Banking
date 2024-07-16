<?php

namespace App\Libraries\smsServiceProviders;

class KavehNegar
{
    public function send($receptor, $message)
    {
        $api = new \Kavenegar\KavenegarApi(config('app.KAVEH_NEGAR_API_KEY'));
        $api->Send(config('app.KAVEH_NEGAR_SENDER_NUMBER'), $receptor, $message);
        return true;
    }
}