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
        Schema::create('jadwal_proposal_ta_dosen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_proposal_ta_id');
            $table->foreign('jadwal_proposal_ta_id')->references('id')->on('jadwal_proposal_ta')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('dosen_id');
            $table->foreign('dosen_id')->references('id_dosen')->on('dosen')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_proposal_ta_dosen');
    }
};
