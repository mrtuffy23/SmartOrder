<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Gunakan 'text' karena isian target biasanya panjang/banyak baris
            $table->text('target_produksi')->nullable()->after('fabric_id');
            $table->text('target_packing')->nullable()->after('target_produksi');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['target_produksi', 'target_packing']);
        });
    }
};
