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
        Schema::create('auth_user', function (Blueprint $table) {
            $table->id();
            $table->string('role')->nullable();
            //make column nim and connect it to nim in mahasiswa table, it's string with collation latin1_swedish_ci
            $table->string('nim')->collation('latin1_swedish_ci')->nullable();
            //connect nim to nim in mahasiswa table
            $table->foreign('nim')->references('nim')->on('mahasiswa')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('id_dosen')->nullable();
            $table->foreign('id_dosen')->references('id_dosen')->on('dosen')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('id_admin')->nullable();
            $table->foreign('id_admin')->references('id_admin')->on('admin')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_user');
    }
};
