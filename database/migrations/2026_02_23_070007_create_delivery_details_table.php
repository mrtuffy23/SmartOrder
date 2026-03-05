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
        $table->foreignId('delivery_id')->constrained('deliveries')->onDelete('cascade');
        $table->foreignId('pemartaian_detail_id')->constrained('pemartaian_details')->onDelete('restrict');
        
        // 👇 TAMBAHAN BARU: Buyer & Warna ada di setiap baris 👇
        $table->foreignId('buyer_id')->constrained('buyers')->onDelete('restrict');
        $table->foreignId('color_id')->constrained('colors')->onDelete('restrict');
        
        $table->string('no_order')->nullable();
        $table->string('no_roda')->nullable();
        $table->string('keterangan')->nullable();
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
