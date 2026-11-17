<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrNew(['email' => 'admin@admin.com']);

        if (! $admin->exists) {
            $admin->name = 'Administrador';
            $admin->password = Hash::make('123123');
            $admin->is_admin = true;
            $admin->save();
        }
    }
}
