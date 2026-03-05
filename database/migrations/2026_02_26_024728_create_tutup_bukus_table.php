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
    Schema::create('tutup_bukus', function (Blueprint $table) {
        $table->id();
        $table->string('bulan', 7)->unique(); // Format akan berisi 'YYYY-MM' (Contoh: 2026-02)
        $table->enum('status', ['open', 'closed'])->default('closed');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutup_bukus');
    }
};
