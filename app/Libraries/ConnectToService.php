<?php

namespace App\Libraries;

class ConnectToService
{
    public $smsProvider;

    public function sendSms($mobile, $message)
    {
        $this->smsProvider->send($mobile, $message);
    }
}