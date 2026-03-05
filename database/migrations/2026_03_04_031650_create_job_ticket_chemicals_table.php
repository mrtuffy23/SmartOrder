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
        Schema::create('job_ticket_chemicals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_ticket_id')->constrained('job_tickets')->onDelete('cascade');
            $table->foreignId('chemical_id')->constrained('chemicals')->onDelete('restrict');
            
            $table->decimal('concentration', 10, 5); 
            $table->decimal('gram', 10, 2); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_ticket_chemicals');
    }
};
