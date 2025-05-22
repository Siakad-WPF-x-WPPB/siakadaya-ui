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

      // Foreign key constraints
      $table->foreignUuid('prodi_id')->constrained('program_studi')->onDelete('cascade');
      $table->foreignUuid('kelas_id')->constrained('kelas')->onDelete('cascade');

      // Student details
      $table->string('nrp', 12)->unique();
      $table->string('nama', 100)->index();
      $table->enum('jenis_kelamin', ['L', 'P']);

      // Contact details
      $table->char('telepon', 15)->nullable();
      $table->string('email')->unique();
      $table->string('password');

      // Personal information
      $table->string('agama', 20);
      $table->string('semester', 6);
      $table->date('tanggal_lahir');
      $table->date('tanggal_masuk');
      $table->enum('status', ['Aktif', 'Cuti', 'Keluar'])->default('Aktif')->index();

      // Address details
      $table->text('alamat_jalan')->nullable();
      $table->string('provinsi', 50);
      $table->string('kode_pos', 50);
      $table->string('negara', 50)->default('Indonesia');
      $table->string('kelurahan', 50);
      $table->string('kecamatan', 50);
      $table->string('kota', 50);

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
