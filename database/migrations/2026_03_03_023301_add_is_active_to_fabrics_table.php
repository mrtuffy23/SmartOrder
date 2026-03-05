<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('fabrics', function (Blueprint $table) {
            // Kita pakai tipe boolean (1 = Aktif, 0 = Non-Aktif). Default-nya 1 (Aktif).
            $table->boolean('is_active')->default(true)->after('density');
        });
    }

    public function down()
    {
        Schema::table('fabrics', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
