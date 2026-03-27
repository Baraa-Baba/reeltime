<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        user::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '123456', 
            'profile_image' => 'https://robohash.org/admin',
            'member_since' => now(),
            'role' => 'admin',
        ]);


        User::create([
            'username' => 'user',
            'email' => 'user@gmail.com',
            'password' => '123456',
            'profile_image' => 'https://robohash.org/user',
            'member_since' => now(),
            'role' => 'user',  
        ]);
    }
}
