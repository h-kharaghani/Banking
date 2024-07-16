<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Card;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cardNums = ['6274129005473742', '6037697570194701'];
        foreach ($cardNums as $cardNum) {

            Card::create([
                'account_id' => Account::first()->id,
                'number' => $cardNum,
                'balance' => 500000,
                'state' => Card::state_activated,
                'type' => Card::normal,
                'issue_date' => now(),
                'end_date' => now()->addYear(),
            ]);
        }
    }
}
