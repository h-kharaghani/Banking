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
        $users = User::all();
        foreach ($users as $key => $user) {
            Account::create([
                'user_id' => $user->id,
                'bank_id' => 1,
                'type' => Account::saving_account_type,
                'state' => Account::state_activated,
                'number' => rand(10000000000, 9999999999999),
                'sheba' => '1235678912345678912345' . $key,
                'balance' => '2000000',
                'end_date' => now()->addYear(),
            ]);
        }
    }
}
