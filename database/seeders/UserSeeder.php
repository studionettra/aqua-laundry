<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Level / Role Pengguna
        $adminLevel = Level::firstOrCreate(['level_name' => 'Administrator']);
        $operatorLevel = Level::firstOrCreate(['level_name' => 'Operator']);
        $pimpinanLevel = Level::firstOrCreate(['level_name' => 'Pimpinan']);

        // 2. Akun Demo Administrator
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'id_level' => $adminLevel->id,
                'name' => 'Admin Aqua Laundry',
                'password' => Hash::make('12345678'),
            ]
        );

        // 3. Akun Demo Operator / Kasir
        User::updateOrCreate(
            ['email' => 'operator@gmail.com'],
            [
                'id_level' => $operatorLevel->id,
                'name' => 'Operator Kasir',
                'password' => Hash::make('12345678'),
            ]
        );

        // 4. Akun Demo Pimpinan / Pemilik Toko
        User::updateOrCreate(
            ['email' => 'pimpinan@gmail.com'],
            [
                'id_level' => $pimpinanLevel->id,
                'name' => 'Bapak Pimpinan',
                'password' => Hash::make('12345678'),
            ]
        );
    }
}
