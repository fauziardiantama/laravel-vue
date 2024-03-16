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
        Schema::create('jadwal_proposal_ta_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_proposal_ta_id');
            $table->foreign('jadwal_proposal_ta_id')->references('id')->on('jadwal_proposal_ta')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('proposal_ta_id');
            $table->foreign('proposal_ta_id')->references('id')->on('proposal_ta')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_proposal_ta_mahasiswa');
    }
};
