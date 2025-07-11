<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nik' => '001',
                'nama' => 'Sonia',
                'password' => Hash::make('sonia123'), // ganti sesuai kebutuhan
                'role' => 'operator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '002',
                'nama' => 'Iqbal',
                'password' => Hash::make('iqbal123'), // ganti sesuai kebutuhan
                'role' => 'supervisor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
