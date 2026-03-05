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
        $table->foreignId('pemartaian_id')->constrained('pemartaians')->onDelete('cascade');
        
        $table->string('no_order')->nullable();
        $table->foreignId('fabric_id')->constrained('fabrics')->onDelete('restrict');
        $table->string('warna')->nullable();
        $table->string('no_batch')->unique(); // Auto-number dari sistem
        $table->integer('jml_gulung');
        $table->decimal('total_meter', 10, 2);
        $table->decimal('berat', 10, 2)->nullable();
        
        // 👇 TAMBAHAN BARU DARI MANAJER 👇
        $table->string('keterangan')->nullable(); 
        
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
