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
    Schema::create('receipt_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('receipt_id')->constrained('receipts')->onDelete('cascade');
        $table->foreignId('fabric_id')->constrained('fabrics')->onDelete('restrict'); // Corak
        $table->decimal('total_meter', 10, 2);
        $table->string('no_order')->nullable();
        $table->string('keterangan')->nullable();
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_details');
    }
};
