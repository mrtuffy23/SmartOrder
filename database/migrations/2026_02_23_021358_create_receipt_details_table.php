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
        // Nyambung ke tabel receipts (Induk)
        $table->foreignId('receipt_id')->constrained('receipts')->onDelete('cascade');
        
        // NYAMBUNG KE TABEL KAIN (Ini yang kamu maksud!) 👈
        $table->foreignId('fabric_id')->constrained('fabrics')->onDelete('restrict');
        
        $table->decimal('total_meter', 10, 2)->default(0);
        $table->string('no_order')->nullable(); // Sesuai foto ada kolom "Order"
        $table->integer('jml_batch')->default(0);
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
