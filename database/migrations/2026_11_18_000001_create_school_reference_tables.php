<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provinsi', function (Blueprint $table) {
            $table->unsignedInteger('id_provinsi')->primary();
            $table->string('nama_provinsi', 100);
        });

        Schema::create('kabupaten', function (Blueprint $table) {
            $table->unsignedInteger('id_kabupaten')->primary();
            $table->string('nama_kabupaten')->nullable();
            $table->unsignedInteger('id_provinsi')->nullable()->index();
        });

        Schema::create('kecamatan', function (Blueprint $table) {
            $table->unsignedInteger('id_kecamatan')->primary();
            $table->unsignedInteger('id_kabupaten')->nullable()->index();
            $table->string('nama_kecamatan')->nullable();
        });

        Schema::create('sekolah', function (Blueprint $table) {
            $table->unsignedInteger('id_sekolah')->primary();
            $table->string('nama_sekolah')->nullable()->index();
            $table->string('npsn', 20)->nullable()->index();
            $table->string('jenjang', 20);
            $table->string('status_sekolah', 20);
            $table->text('alamat_sekolah');
            $table->unsignedInteger('id_kabupaten')->index();
            $table->unsignedInteger('id_kecamatan')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sekolah');
        Schema::dropIfExists('kecamatan');
        Schema::dropIfExists('kabupaten');
        Schema::dropIfExists('provinsi');
    }
};
