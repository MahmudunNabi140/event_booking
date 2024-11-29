<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
        foreach ($this->datas() as $key => $value) {
            User::create($value);
        }
    }

    private function datas()
    {
        return [
            [
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => Hash::make('12345678'), // It's important to hash the password
            'current_team_id' => null, // You can set a team if needed
            'profile_photo_path' => null,
            'created_at' => now(),
            ],
            
        ];
    }
}
