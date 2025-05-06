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
            $table->id();
            $table->foreignId('kode_jurusan')->constrained('jurusan')->onDelete('cascade');
            $table->string('nama_dosen');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->char('telepon', length: 15);
            $table->string('email')->unique();
            $table->string('password');
            $table->date('tanggal_lahir');
            $table->string('jabatan');
            $table->string('golongan_akhir');
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
