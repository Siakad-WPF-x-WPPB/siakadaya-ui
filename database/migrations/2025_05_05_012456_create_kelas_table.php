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
        Schema::create('kelas', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Foreign key constraints
            $table->foreignUuid('prodi_id')->constrained('program_studi')->onDelete('cascade');
            $table->foreignUuid('dosen_id')->constrained('dosen')->onDelete('cascade')->nullable();

            // Kelas details
            $table->string('pararel', 10)->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
