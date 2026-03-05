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
        Schema::create('process_chemicals', function (Blueprint $table) {
            $table->id();
            // Menyambungkan ke tabel processes
            $table->foreignId('process_id')->constrained()->onDelete('cascade');
            // Menyambungkan ke tabel chemicals
            $table->foreignId('chemical_id')->constrained()->onDelete('cascade');
            // Kolom untuk menyimpan takaran konsentrasi (contoh: "0,5" atau "3")
            $table->string('concentration'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_chemicals');
    }
};
