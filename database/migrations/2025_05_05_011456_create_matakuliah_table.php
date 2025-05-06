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
        Schema::create('matakuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kode_jurusan')->constrained('jurusan')->onDelete('cascade');
            $table->string('nama_matakuliah');
            $table->string('sks');
            $table->string('semester');
            $table->enum('tipe_matakuliah', ['MPK', 'MPI', 'MW']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matakuliah');
    }
};
