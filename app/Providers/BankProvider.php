<?php

namespace App\Providers;

use Facades\App\Libraries\BankValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class BankProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $validators = [
            'ir_card_number' => 'irCardNumber'
        ];
        foreach ($validators as $name => $method) {
            Validator::extend($name, function ($message, $attribute, $rule, $parameters) {
                $sum = 0;
                for ($pos = 1; $pos <= 16; $pos++) {
                    $tmp = $attribute[$pos - 1];
                    $tmp = $pos % 2 === 0 ? $tmp : $tmp * 2;
                    $tmp = $tmp > 9 ? $tmp - 9 : $tmp;
                    $sum += $tmp;
                }
                return $sum % 10 == 0;
            }, $name);
        }
    }
}
