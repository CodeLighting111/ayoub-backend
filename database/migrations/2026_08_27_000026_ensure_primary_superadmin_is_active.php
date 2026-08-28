<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $superadminRoleId = DB::table('roles')->where('slug', 'superadmin')->value('id');

        DB::table('admins')
            ->where('email', 'ayoub@gmail.com')
            ->update([
                'status' => 'active',
                'role' => 'superadmin',
                'role_id' => $superadminRoleId,
            ]);
    }

    public function down(): void
    {
        // Keep primary superadmin active; no rollback needed.
    }
};
