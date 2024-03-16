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
            $table->string('token')->nullable();
            $table->timestamp('token_expired')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposal_ta', function (Blueprint $table) {
            $table->dropColumn('token');
            $table->dropColumn('token_expired');
        });
    }
};
