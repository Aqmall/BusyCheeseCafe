<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks
        Schema::disableForeignKeyConstraints();

        // Hapus data user lama jika ada untuk menghindari duplikat saat seeding ulang
        User::truncate();

        // Aktifkan kembali foreign key checks
        Schema::enableForeignKeyConstraints();

        // Buat Akun Kasir
        User::create([
            'name' => 'Kasir',
            'email' => 'kasir@busycheese.com',
            'password' => Hash::make('password'),
        ]);

        // Buat Akun Manager
        User::create([
            'name' => 'Manager',
            'email' => 'manager@busycheese.com',
            'password' => Hash::make('password'),
        ]);
    }
}