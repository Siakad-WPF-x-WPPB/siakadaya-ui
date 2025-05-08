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
      $table->uuid('id')->primary();
      $table->foreignId('prodi_id')->constrained('program_studi')->onDelete('cascade');
      $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
      $table->string('nrp')->unique();
      $table->string('nama');
      $table->enum('jenis_kelamin', ['L', 'P']);
      $table->char('telepon', length: 15);
      $table->string('email')->unique();
      $table->string('password');
      $table->string('agama');
      $table->enum('semester', ['1', '2', '3', '4', '5', '6', '7', '8']);
      $table->date('tanggal_lahir');
      $table->date('tanggal_masuk');
      $table->enum('status', ['Aktif', 'Cuti', 'Keluar']);
      $table->text('alamat_jalan');
      $table->string('provinsi');
      $table->string('kode_pos');
      $table->string('negara');
      $table->string('kelurahan');
      $table->string('kecamatan');
      $table->string('kota');
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
