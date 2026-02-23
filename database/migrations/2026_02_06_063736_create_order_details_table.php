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
    Schema::create('order_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->cascadeOnDelete(); // Nempel ke Order Utama
        $table->foreignId('color_id')->constrained(); // Warna

        // Kolom sesuai tabel di kertas
        $table->integer('qty_om')->default(0);      // JML OM
        $table->decimal('batch_size', 10, 2)->default(0); // Batch Size
        $table->integer('jml_batch')->default(0);   // Jml Batch
        $table->decimal('jml_grey', 10, 2)->default(0);   // Jml Grey
        $table->string('notes')->nullable();        // Keterangan per baris

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
