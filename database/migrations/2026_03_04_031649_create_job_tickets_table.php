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
        Schema::create('job_tickets', function (Blueprint $table) {
            $table->id();
            // Menyimpan nomor urut tiket (Misal: 260326)
            $table->string('ticket_code')->unique(); 
            $table->date('tanggal');
            
            // Relasi ke tabel Order Kerja
            $table->foreignId('order_id')->constrained('orders')->onDelete('restrict');
            
            // Input manual berat kain di lapangan
            $table->decimal('fabric_weight', 8, 2); 
            
            // Relasi ke Mesin & Proses
            $table->foreignId('machine_id')->constrained('machines')->onDelete('restrict');
            $table->foreignId('process_id')->constrained('processes')->onDelete('restrict');
            
            // Snapshot Volume Air (berjaga-jaga kalau master mesin diubah di masa depan)
            $table->integer('volume'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_tickets');
    }
};
