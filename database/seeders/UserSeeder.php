<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $mobiles = ['09198274029', '09381162673' , '09121121212'];
        foreach ($mobiles as $key => $mobile) {
            User::create([
                'name' => 'user_' . $key + 1,
                'mobile' => $mobile,
                'password' => '12345678',
            ]);
        }
    }
}
