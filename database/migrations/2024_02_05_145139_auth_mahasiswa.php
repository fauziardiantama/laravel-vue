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
        Schema::create('auth_mahasiswa', function (Blueprint $table) {
            $table->id();
            //make column nim and connect it to nim in mahasiswa table, it's string with collation latin1_swedish_ci
            $table->string('nim')->collation('latin1_swedish_ci');
            //connect nim to nim in mahasiswa table
            $table->foreign('nim')->references('nim')->on('mahasiswa')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_mahasiswa');
    }
};
