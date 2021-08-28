<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Aamish Irfan',
            'email' => 'aamishirfan2662@gmail.com',
            'password' => bcrypt('password')
        ]);
        User::create([
            'name' => 'Test 1',
            'email' => 'test1@gmail.com',
            'password' => bcrypt('password')
        ]);
        User::create([
            'name' => 'Test 2',
            'email' => 'test2@gmail.com',
            'password' => bcrypt('password')
        ]);
    }
}
