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
        Schema::table('proposal_ta', function (Blueprint $table) {
            $table->unsignedBigInteger('semester_id');
            $table->foreign('semester_id')->references('id')->on('semester')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposal_ta', function (Blueprint $table) {
            //
        });
    }
};
