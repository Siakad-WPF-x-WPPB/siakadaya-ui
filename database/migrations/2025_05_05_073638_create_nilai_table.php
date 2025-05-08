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
            $table->uuid('id')->primary();
            $table->foreignUuid('dosen_id')->constrained('dosen')->onDelete('cascade');
            $table->foreignUuid('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignUuid('mk_id')->constrained('matakuliah')->onDelete('cascade');
            $table->foreignUuid('tahun_ajar_id')->constrained('tahun_ajar')->onDelete('cascade');
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
