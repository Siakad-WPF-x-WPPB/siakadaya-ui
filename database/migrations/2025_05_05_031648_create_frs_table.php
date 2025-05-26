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
        Schema::create('frs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Foreign key constraints
            $table->foreignUuid('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignUuid('tahun_ajar_id')->constrained('tahun_ajar')->onDelete('cascade');

            // FRS details
            $table->date('tanggal_pengisian');

            $table->timestamps();

            // $table->unique(['mahasiswa_id', 'tahun_ajar_id'], 'unique_frs_per_mahasiswa_tahun_ajar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frs');
    }
};
