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
        Schema::create('jadwal_proposal_ta', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->year('tahun')->nullable();
            $table->foreign('tahun')->references('tahun')->on('tahun')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('semester_id');
            $table->foreign('semester_id')->references('id')->on('semester')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('sesi_id');
            $table->foreign('sesi_id')->references('id')->on('sesi_ujian')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('ruangan_id');
            $table->foreign('ruangan_id')->references('id')->on('ruangan')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_proposal_ta');
    }
};
