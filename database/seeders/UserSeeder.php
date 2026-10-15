<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Arvin Noer Hakim',
            'username' => 'hakimarvinnoer',
            'email' => null,
            'password' => Hash::make('12345678'),
        ]);
    }
}