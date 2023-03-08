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
        Schema::create('progetti', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->foreignId('cliente_id')->constrained('clienti');
            $table->foreignId('pjm_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progettos');
    }
};
