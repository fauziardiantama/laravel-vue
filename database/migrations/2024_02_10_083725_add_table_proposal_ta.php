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
        Schema::create('proposal_ta', function (Blueprint $table) {
            $table->id();
            $table->string('judul_proposal');
            $table->string('file_proposal');
            $table->integer('status_proposal')->nullable();
            $table->string('nim')->collation('latin1_swedish_ci')->nullable();
            $table->foreign('nim')->references('nim')->on('mahasiswa')->onUpdate('cascade')->onDelete('cascade');
            $table->year('tahun')->nullable();
            $table->foreign('tahun')->references('tahun')->on('tahun')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_ta');
    }
};
