<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Account::create([
            'user_id' => User::first()->id,
            'bank_id' => 1,
            'type' => Account::saving_account_type,
            'state' => Account::state_activated,
            'number' => '1111111111',
            'sheba' => '12356789123456789123456',
            'balance' => '2000000',
            'end_date' => now()->addYear(),
        ]);
    }
}
