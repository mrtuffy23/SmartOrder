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
        $table->string('no_bukti')->unique(); // Contoh: 026544
        $table->date('tgl_terima');           // Contoh: 02 Mar 2022
        $table->string('terima_dari')->nullable(); // Contoh: Gudang Grey
        $table->string('keterangan')->nullable();
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
