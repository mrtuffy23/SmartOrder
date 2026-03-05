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
    Schema::create('receipts', function (Blueprint $table) {
        $table->id();
        $table->string('no_bukti')->unique(); // Auto-number
        $table->date('tanggal');
        $table->string('terima'); // Sesuai Excel: misal diisi "GUDANG GREIGE" atau nama Supplier
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
