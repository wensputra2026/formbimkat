<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop foreign key dan kolom jabatan_id dari tabel gurus jika ada
        if (Schema::hasTable('gurus') && Schema::hasColumn('gurus', 'jabatan_id')) {
            Schema::table('gurus', function (Blueprint $table) {
                // Drop foreign key first
                try {
                    $table->dropForeign(['jabatan_id']);
                } catch (\Throwable $e) {
                    // ignore if foreign key has different name
                }
                $table->dropColumn('jabatan_id');
            });
        }

        // 2. Drop tabel jabatans
        Schema::dropIfExists('jabatans');
    }

    public function down(): void
    {
        Schema::create('jabatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jabatan', 100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        if (Schema::hasTable('gurus') && !Schema::hasColumn('gurus', 'jabatan_id')) {
            Schema::table('gurus', function (Blueprint $table) {
                $table->foreignId('jabatan_id')->nullable()->after('pangkat_golongan_id')->constrained('jabatans')->nullOnDelete();
            });
        }
    }
};
