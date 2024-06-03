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
        Schema::create('penguji_seminar', function (Blueprint $table) {
            $table->id();
            $table->integer('id_seminar');
            $table->foreign('id_seminar')->references('id_seminar')->on('seminar')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('id_dosen');
            $table->foreign('id_dosen')->references('id_dosen')->on('dosen')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penguji_seminar');
    }
};
