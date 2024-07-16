<?php

namespace App\Providers;

use App\Libraries\Helper;
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
        $this->validateIrCardNumber();
        $this->validatePersianNumber();
    }

    private function validateIrCardNumber()
    {
        $name = 'ir_card_number';
        Validator::extend($name, function ($message, $attribute, $rule, $parameters) {
            $attr = Helper::convertArabicToEnglish($attribute);
            $sum = 0;
            for ($pos = 1; $pos <= 16; $pos++) {
                $tmp = $attr[$pos - 1];
                $tmp = $pos % 2 === 0 ? $tmp : $tmp * 2;
                $tmp = $tmp > 9 ? $tmp - 9 : $tmp;
                $sum += $tmp;
            }
            return $sum % 10 == 0;
        }, $name);
    }

    private function validatePersianNumber()
    {
        $name = 'persian_number';
        Validator::extend($name, function ($attributes, &$value, $parameters, $validation) {
            return preg_match("/^[\x{0660}-\x{661}\x{662}\x{663}\x{664}\x{665}\x{666}\x{667}\x{668}\x{669}\d\s]+$/u", $value);
        });
    }
}
