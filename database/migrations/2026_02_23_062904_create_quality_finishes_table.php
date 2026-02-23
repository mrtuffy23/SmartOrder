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
    Schema::create('quality_finishes', function (Blueprint $table) {
        $table->id();
        
        // Relasi ke rincian Pemartaian (Kain spesifik mana yang di-finish)
        $table->foreignId('pemartaian_detail_id')->constrained('pemartaian_details')->onDelete('cascade');
        
        $table->date('tanggal_finish');
        
        // Hasil dari proses produksi (Bisa jadi meternya menyusut atau bertambah sedikit)
        $table->decimal('hasil_meter', 10, 2)->default(0); 
        $table->decimal('hasil_berat', 10, 2)->nullable()->default(0);
        
        // Grade kualitas kain (Grade A bagus, Grade B ada cacat, dsb)
        $table->string('grade')->default('A'); 
        
        $table->string('keterangan')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_finishes');
    }
};
