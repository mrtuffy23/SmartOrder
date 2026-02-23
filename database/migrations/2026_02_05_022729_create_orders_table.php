<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('po_number'); // No PO
        $table->string('mf_number')->nullable(); // No MF
        $table->date('order_date');  // Tanggal
        
        // Relasi (Kunci Asing)
        $table->foreignId('buyer_id')->constrained('buyers')->cascadeOnDelete();
        $table->foreignId('fabric_id')->constrained('fabrics')->cascadeOnDelete();
        
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
