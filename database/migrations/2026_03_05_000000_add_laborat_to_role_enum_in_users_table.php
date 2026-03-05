<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('superadmin', 'admin', 'gudang', 'produksi', 'laborat') NOT NULL DEFAULT 'gudang'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('superadmin', 'admin', 'gudang', 'produksi') NOT NULL DEFAULT 'gudang'");
    }
};
