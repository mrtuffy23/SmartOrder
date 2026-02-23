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
    Schema::create('pemartaians', function (Blueprint $table) {
        $table->id();
        $table->string('no_partai')->unique(); // Contoh: 22384
        $table->date('tanggal');               // Contoh: 04 Feb 2022
        $table->string('jenis_pengeluaran')->nullable(); // Contoh: PRODUKSI / ORDER KERJA
        $table->string('keterangan')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemartaians');
    }
};
