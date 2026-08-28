<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $superadminRoleId = \App\Models\Role::query()->where('slug', 'superadmin')->value('id');

        Admin::query()->updateOrCreate(
            ['email' => 'ayoub@gmail.com'],
            [
                'name' => 'Ayoub',
                'phone' => '01012345678',
                'password' => 'admin123',
                'role' => 'superadmin',
                'role_id' => $superadminRoleId,
                'status' => 'active',
            ],
        );
    }
}
