<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if ($this->command->confirm('Do you want to add first user ?'))
        {
            $this->call(UserSeeder::class);
        }
        if ($this->command->confirm('Do you want to add card Number ?'))
        {
            $this->call(CardSeeder::class);
        }
        User::factory()->create([
            'name' => 'Hamid',
            'mobile' => '09198274029',
            'password' => '123456',
        ]);
        //todo: add accounts seeder
    }
}
