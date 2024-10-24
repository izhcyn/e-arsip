<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'incayyy',
                'username' => 'incayy',
                'email' => 'incay@gmail.com',
                'email_verified_at' => null,
                'password' => Hash::make('12345678'), // Replace with your hashed password
                'role' => 'super_admin',
            ],
            [
                'id' => 3,
                'name' => 'karina',
                'username' => 'karina',
                'email' => 'karinaespa@gmail.com',
                'email_verified_at' => null,
                'password' => Hash::make('12345678'), // Replace with your hashed password
                'role' => 'admin',
            ],
            [
                'id' => 4,
                'name' => 'Giselle',
                'username' => 'jjjell',
                'email' => 'jjjell@gmail.com',
                'email_verified_at' => null,
                'password' => Hash::make('12345678'), // Replace with your hashed password
                'role' => 'user',
            ],
        ]);
    }
}
