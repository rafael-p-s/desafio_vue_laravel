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
        Schema::create('empresa_setor', function (Blueprint $table) {
            $table->integer('empresa_id');
            $table->integer('setor_id');

            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('setor_id')->references('id')->on('setor')->onDelete('cascade');

            $table->primary(['empresa_id', 'setor_id']); // chave composta;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa_setor');
    }
};
