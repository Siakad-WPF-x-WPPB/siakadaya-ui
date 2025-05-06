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
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nip')->constrained('dosen')->onDelete('cascade');
            $table->foreignId('nrp')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('kode_matakuliah')->constrained('matakuliah')->onDelete('cascade');
            $table->foreignId('id_tahun_ajar')->constrained('tahun_ajar')->onDelete('cascade');
            $table->enum('status', ['lulus', 'tidak lulus']);
            $table->enum('nilai_huruf', ['A', 'AB', 'B', 'BC', 'C', 'D', 'E']);
            $table->integer('nilai_angka');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};
