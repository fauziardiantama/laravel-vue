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
        Schema::create('pembimbing_ta', function (Blueprint $table) {
            $table->id();
            $table->integer('id_magang')->nullable();
            $table->foreign('id_magang')->references('id_magang')->on('magang')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('pembimbing_ta');
    }
};
