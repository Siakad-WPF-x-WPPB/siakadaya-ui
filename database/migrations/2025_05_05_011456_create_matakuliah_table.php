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
            $table->uuid('id')->primary();

            // Foreign key constraints
            $table->foreignUuid('prodi_id')->constrained('program_studi')->onDelete('cascade');

            // Matakuliah details
            $table->string('kode')->unique();
            $table->string('nama', 100)->index();
            $table->string('semester', 6);
            $table->integer('sks');
            $table->enum('tipe', ['MW', 'MPP', 'MPI', 'MPK', 'MBKM']);

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
