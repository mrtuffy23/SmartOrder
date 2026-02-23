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
    Schema::create('delivery_details', function (Blueprint $table) {
        $table->id();
        // Relasi ke tabel Induk Surat Jalan
        $table->foreignId('delivery_id')->constrained('deliveries')->onDelete('cascade');
        
        // Relasi ke Barang Jadi (Kain yang sudah di-finish)
        $table->foreignId('quality_finish_id')->constrained('quality_finishes')->onDelete('restrict');
        
        $table->integer('jml_roll')->default(0);
        $table->decimal('total_meter', 10, 2)->default(0);
        $table->decimal('total_berat', 10, 2)->nullable()->default(0);
        
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_details');
    }
};
