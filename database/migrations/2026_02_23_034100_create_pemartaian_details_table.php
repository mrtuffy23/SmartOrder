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
    Schema::create('pemartaian_details', function (Blueprint $table) {
        $table->id();
        // Relasi ke tabel induk (Pemartaian)
        $table->foreignId('pemartaian_id')->constrained('pemartaians')->onDelete('cascade');
        
        // Data Rincian (Sesuai kolom di foto aplikasi lama)
        $table->string('no_order')->nullable(); // Contoh: OK/22/10070
        $table->foreignId('fabric_id')->constrained('fabrics')->onDelete('restrict'); // Kode & Corak
        $table->string('warna')->nullable();
        $table->string('no_batch')->nullable(); // Pakai string karena kadang ada huruf misal "4A"
        $table->integer('jml_gulung')->default(0);
        $table->decimal('total_meter', 10, 2)->default(0);
        $table->decimal('berat', 10, 2)->nullable()->default(0);
        
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemartaian_details');
    }
};
