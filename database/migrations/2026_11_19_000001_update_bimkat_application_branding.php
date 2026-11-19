<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'app_name'],
            ['value' => 'Pendataan Guru.', 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'instansi'],
            ['value' => 'BIMAS Katolik Kantor Kementerian Agama Kabupaten Timor Tengah Selatan', 'created_at' => now(), 'updated_at' => now()]
        );
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'app_name')->update([
            'value' => 'Pendataan Guru Kementerian Agama',
            'updated_at' => now(),
        ]);

        DB::table('settings')->where('key', 'instansi')->update([
            'value' => 'Kantor Kementerian Agama Kabupaten Timor Tengah Selatan',
            'updated_at' => now(),
        ]);
    }
};
