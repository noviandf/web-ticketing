<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Kategori Masalah (Gunakan firstOrCreate agar tidak duplikat jika dijalankan 2x)
        Category::firstOrCreate(['name' => 'Hardware (Laptop/PC/Printer)']);
        Category::firstOrCreate(['name' => 'Software (Aplikasi/OS)']);
        Category::firstOrCreate(['name' => 'Network (Internet/Wifi)']);
        Category::firstOrCreate(['name' => 'User Access (Login/Email)']);

        // Buat Akun Utama(Admin)
        User::updateOrCreate(
            ['email' => 'user1@kantor.id'], // Kunci pencarian
            [
                'name' => 'user 1',
                'role' => 'admin',
                'password' => Hash::make('password_user1'),
            ]
        );

        // Buat Akun User Biasa (Agent)
        User::updateOrCreate(
            ['email' => 'user2@kantor.id'], // Kunci pencarian
            [
                'name' => 'user 2',
                'role' => 'agent',
                'password' => Hash::make('password_user2'),
            ]
        );

        // Buat Akun Team Leader (TL)
        User::updateOrCreate(
            ['email' => 'user3@kantor.id'], // Kunci pencarian
            [
                'name' => 'user 3',
                'role' => 'tl',
                'password' => Hash::make('password_user3'),
            ]
        );

        // Buat Akun Management
        User::updateOrCreate(
               ['email' => 'user4@kantor.id'], // Kunci pencarian
            [
                'name' => 'user 4',
                'role' => 'management',
                'password' => Hash::make('password_user4'),
            ]
        );
    }
}
