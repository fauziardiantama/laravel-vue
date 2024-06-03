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
        Schema::table('nilai_seminar', function (Blueprint $table) {
            $table->integer('id_dosen')->nullable();
            $table->foreign('id_dosen')->references('id_dosen')->on('dosen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_seminar', function (Blueprint $table) {
            //
        });
    }
};
