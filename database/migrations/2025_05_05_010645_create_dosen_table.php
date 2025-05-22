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
        Schema::create('dosen', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Foreign key constraints
            $table->foreignUuid('prodi_id')->constrained('program_studi')->onDelete('cascade');

            // Dosen details
            $table->string('nip', 18)->unique();
            $table->string('nama', 100)->index();
            $table->enum('jenis_kelamin', ['L', 'P']);

            // Contact details
            $table->char('telepon', 15)->nullable();
            $table->string('email')->unique();
            $table->string('password');

            // Personal information
            $table->date('tanggal_lahir');
            $table->string('jabatan', 50)->index();
            $table->string('golongan_akhir', 10);
            $table->boolean('is_wali');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
