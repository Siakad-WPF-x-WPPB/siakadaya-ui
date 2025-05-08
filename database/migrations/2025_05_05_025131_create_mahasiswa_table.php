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
    Schema::create('mahasiswa', function (Blueprint $table) {
      $table->uuid('mahasiswa_id')->primary();
      $table->foreignId('prodi_id')->constrained('program_studi')->onDelete('cascade');
      $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
      $table->string('nrp')->unique();
      $table->string('nama_mahasiswa');
      $table->enum('jenis_kelamin', ['L', 'P']);
      $table->char('telepon', length: 15);
      $table->string('email')->unique();
      $table->string('password');
      $table->date('tanggal_lahir');
      $table->date('tanggal_masuk');
      $table->enum('status', ['Aktif', 'Cuti', 'Keluar']);
      $table->text('alamat');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('mahasiswa');
  }
};
