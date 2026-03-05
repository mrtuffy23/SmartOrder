<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('job_tickets', function (Blueprint $table) {
            // Tambahkan kolom color_id setelah order_id
            $table->foreignId('color_id')->nullable()->after('order_id')->constrained('colors')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_tickets', function (Blueprint $table) {
            //
        });
    }
};
