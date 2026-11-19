<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('gurus') && Schema::hasColumn('gurus', 'jabatan_id')) {
            Schema::table('gurus', function (Blueprint $table) {
                $table->dropForeign(['jabatan_id']);
                $table->dropColumn('jabatan_id');
            });
        }
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
        Schema::table('gurus', function (Blueprint $table) {
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatans')->nullOnDelete();
        });
    }
};
