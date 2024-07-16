<?php

namespace App\Libraries\smsServiceProviders;

use App\Exceptions\BankException;
use Illuminate\Support\Facades\Http;

class Ghasedak
{
    public function send($receptor, $message)
    {
        $request = Http::withHeaders(['apikey' => config('app.GHASEDAK_API_KEY')])
            ->post(config('app.GHASEDAK_URL'), [
                'message' => $message,
                'Receptor' => $receptor,
                'sender' => config('app.GHASEDAK_SENDER_NUMBER'),
            ]);
        if ($request->ok()) {
            return true;
        }
        throw_if($request->serverError() or $request->clientError(),
            new BankException('sendSmsError'));
    }
}