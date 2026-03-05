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
        Schema::create('order_recipe_chemicals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_recipe_id')->constrained('order_recipes')->onDelete('cascade');
            $table->foreignId('chemical_id')->constrained('chemicals')->onDelete('restrict');
            
            // Menyimpan takaran g/L (Contoh: 0.5)
            $table->decimal('concentration', 10, 5); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_recipe_chemicals');
    }
};
