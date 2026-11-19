<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jabatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jabatan', 100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('pangkat_golongans', function (Blueprint $table) {
            $table->id();
            $table->string('golongan', 20);
            $table->string('pangkat', 100);
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Add denormalized kabupaten, provinsi, and timestamps to sekolah if not present
        if (Schema::hasTable('sekolah')) {
            Schema::table('sekolah', function (Blueprint $table) {
                if (!Schema::hasColumn('sekolah', 'kabupaten')) {
                    $table->string('kabupaten', 100)->nullable()->index()->after('alamat_sekolah');
                }
                if (!Schema::hasColumn('sekolah', 'provinsi')) {
                    $table->string('provinsi', 100)->nullable()->index()->after('kabupaten');
                }
                if (!Schema::hasColumn('sekolah', 'created_at')) {
                    $table->timestamps();
                }
                if (!Schema::hasColumn('sekolah', 'deleted_at')) {
                    $table->softDeletes();
                }
            });

            // Backfill kabupaten and provinsi from existing kabupaten & provinsi tables
            if (Schema::hasTable('kabupaten') && Schema::hasTable('provinsi')) {
                DB::statement("
                    UPDATE sekolah s
                    LEFT JOIN kabupaten k ON s.id_kabupaten = k.id_kabupaten
                    LEFT JOIN provinsi p ON k.id_provinsi = p.id_provinsi
                    SET s.kabupaten = k.nama_kabupaten,
                        s.provinsi = p.nama_provinsi
                    WHERE s.kabupaten IS NULL OR s.provinsi IS NULL
                ");
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('sekolah')) {
            Schema::table('sekolah', function (Blueprint $table) {
                if (Schema::hasColumn('sekolah', 'kabupaten')) {
                    $table->dropColumn('kabupaten');
                }
                if (Schema::hasColumn('sekolah', 'provinsi')) {
                    $table->dropColumn('provinsi');
                }
                if (Schema::hasColumn('sekolah', 'deleted_at')) {
                    $table->dropSoftDeletes();
                }
            });
        }
        Schema::dropIfExists('settings');
        Schema::dropIfExists('pangkat_golongans');
        Schema::dropIfExists('jabatans');
    }
};
