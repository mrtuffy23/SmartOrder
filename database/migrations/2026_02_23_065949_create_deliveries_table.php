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
    Schema::create('deliveries', function (Blueprint $table) {
        $table->id();
        $table->string('no_surat_jalan')->unique(); // Contoh: SJ/26/0001
        $table->date('tanggal_kirim');
        
        // Relasi ke Data Pembeli/Customer
        $table->foreignId('buyer_id')->constrained('buyers')->onDelete('restrict');
        
        $table->string('no_kendaraan')->nullable(); // Plat nomor truk
        $table->string('nama_supir')->nullable();
        $table->string('keterangan')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
