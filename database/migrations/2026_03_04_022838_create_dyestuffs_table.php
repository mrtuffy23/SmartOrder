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
        Schema::create('dyestuffs', function (Blueprint $table) {
            $table->id();
            $table->string('active_code'); // Contoh: BLACK CA
            $table->string('name');        // Contoh: DOMACRON BLACK CA
            $table->string('type', 10)->nullable(); // Contoh: D atau R
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dyestuffs');
    }
};
