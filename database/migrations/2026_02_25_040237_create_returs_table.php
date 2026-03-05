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
    Schema::create('returs', function (Blueprint $table) {
        $table->id();
        $table->string('no_retur')->unique(); // Auto: RTR/26/0001
        $table->date('tanggal');
        
        // Retur mengambil/mengurangi stok dari Gudang Greige
        $table->foreignId('fabric_id')->constrained('fabrics')->onDelete('restrict');
        
        $table->decimal('total_meter', 10, 2);
        $table->string('keterangan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returs');
    }
};
