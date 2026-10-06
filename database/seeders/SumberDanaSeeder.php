<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SumberDana;

class SumberDanaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SumberDana::insert([
            ['name' => 'Bos'],
            ['name' => 'Kas Kantor'],
        ]);
    }
}
