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
        Schema::table('dokumen_registrasi', function (Blueprint $table) {
            $table->string('krs')->nullable()->change();
            $table->string('transkrip')->nullable()->change();
            $table->string('bukti_seminar')->nullable()->change();
            $table->string('kartu_mahasiswa')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumen_registrasi', function (Blueprint $table) {
            $table->string('krs',50)->nullable(false)->change();
            $table->string('transkrip',50)->nullable(false)->change();
            $table->string('bukti_seminar',50)->nullable(false)->change();
            $table->dropColumn('kartu_mahasiswa');
        });
    }
};
