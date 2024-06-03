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
        Schema::table('seminar', function (Blueprint $table) {
            $table->unsignedBigInteger('id_ruangan')->nullable()->default(null)->after('tgl_seminar');
            $table->foreign('id_ruangan')->references('id')->on('ruangan')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seminar', function (Blueprint $table) {
            $table->dropForeign(['id_ruangan']);
            $table->dropColumn('id_ruangan');
        });
    }
};
