<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fabrics', function (Blueprint $table) {
            $table->id();
            $table->string('corak')->unique();
            $table->string('code_kain')->nullable();
            $table->string('quality')->nullable();
            $table->string('buyer_code')->nullable();
            $table->string('brand')->nullable();
            $table->string('construction')->nullable();
            $table->string('density')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fabrics');
    }
};