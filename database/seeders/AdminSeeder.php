<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::firstOrCreate(
            ['username' => 'admin'],
            [
                'nama_admin' => 'Admin Perpus',
                'email' => 'admin@perpusapp.id',
                'password' => 'admin123',
            ]
        );
    }
}
