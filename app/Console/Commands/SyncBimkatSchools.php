<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncBimkatSchools extends Command
{
    protected $signature = 'bimkat:sync-schools';
    protected $description = 'Salin sekolah dan referensi wilayah dari database BIMKAT ke database aplikasi ini';

    public function handle(): int
    {
        $source = DB::connection('bimkat_source');
        foreach (['provinsi', 'kabupaten', 'kecamatan', 'sekolah'] as $table) {
            if (!$source->getSchemaBuilder()->hasTable($table)) {
                $this->error("Tabel sumber {$table} tidak ditemukan.");
                return self::FAILURE;
            }
        }

        $connection = DB::connection();
        $counts = [];
        $connection->transaction(function () use ($source, $connection, &$counts) {
            foreach (['provinsi', 'kabupaten', 'kecamatan', 'sekolah'] as $table) {
                $counts[$table] = ['inserted' => 0, 'updated' => 0, 'unchanged' => 0, 'failed' => 0];
                $source->table($table)->orderBy($table === 'sekolah' ? 'id_sekolah' : ($table === 'provinsi' ? 'id_provinsi' : ($table === 'kabupaten' ? 'id_kabupaten' : 'id_kecamatan')))->chunk(500, function ($rows) use ($connection, $table, &$counts) {
                    foreach ($rows as $row) {
                        $values = (array) $row;
                        $key = match ($table) {
                            'provinsi' => 'id_provinsi', 'kabupaten' => 'id_kabupaten', 'kecamatan' => 'id_kecamatan', default => 'id_sekolah',
                        };
                        try {
                            $old = $connection->table($table)->where($key, $values[$key])->first();
                            if (!$old) {
                                $connection->table($table)->insert($values);
                                $counts[$table]['inserted']++;
                            } elseif ((array) $old !== $values) {
                                $connection->table($table)->where($key, $values[$key])->update($values);
                                $counts[$table]['updated']++;
                            } else {
                                $counts[$table]['unchanged']++;
                            }
                        } catch (\Throwable $e) {
                            $counts[$table]['failed']++;
                            throw $e;
                        }
                    }
                });
            }
        });

        $this->table(['Tabel BIMKAT', 'Baru disalin', 'Diperbarui', 'Tidak berubah', 'Gagal'], collect($counts)->map(fn ($v, $k) => [$k, ...array_values($v)])->values()->all());
        return self::SUCCESS;
    }
}
