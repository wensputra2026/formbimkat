<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_registrasi', 30)->unique();
            $table->string('nama', 150)->index();
            $table->string('nip', 18)->nullable()->unique();
            $table->string('nik', 16)->nullable()->unique();
            $table->foreignId('pangkat_golongan_id')->nullable()->constrained('pangkat_golongans')->nullOnDelete();
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatans')->nullOnDelete();
            $table->decimal('gaji_pokok', 15, 2);
            $table->unsignedTinyInteger('kgb_bulan')->nullable();
            $table->unsignedSmallInteger('kgb_tahun')->nullable();
            $table->unsignedTinyInteger('pensiun_bulan')->nullable();
            $table->unsignedSmallInteger('pensiun_tahun')->nullable();
            $table->string('penerima_tpg', 30)->nullable()->index();
            $table->unsignedInteger('sekolah_id')->index();
            $table->string('status_verifikasi', 25)->default('Menunggu Verifikasi')->index();
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('diverifikasi_pada')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sekolah_id')->references('id_sekolah')->on('sekolah')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
