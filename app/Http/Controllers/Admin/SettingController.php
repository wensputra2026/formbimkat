<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::allKeyed();
        return view('admin.setting.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:255'],
            'instansi' => ['required', 'string', 'max:255'],
            'tahun_pendataan' => ['required', 'string', 'max:10'],
            'alamat_instansi' => ['nullable', 'string', 'max:500'],
            'kontak' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100'],
            'pengumuman' => ['nullable', 'string', 'max:2000'],
        ]);

        foreach ($validated as $key => $val) {
            Setting::set($key, $val);
        }

        ActivityLog::log('update_settings', "Admin " . Auth::user()->name . " memperbarui Pengaturan Aplikasi");

        return back()->with('success', 'Pengaturan aplikasi berhasil disimpan.');
    }

    /**
     * Backup Database ke file SQL
     */
    public function backupDatabase(): StreamedResponse
    {
        ActivityLog::log('backup_database', "Admin " . Auth::user()->name . " mengunduh backup database.");

        $filename = 'backup_db_formbimkat_' . date('Ymd_His') . '.sql';

        return new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "-- Backup Database FormBimkat\n");
            fwrite($handle, "-- Tanggal: " . date('Y-m-d H:i:s') . "\n\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

            $tables = DB::select('SHOW TABLES');
            $dbName = config('database.connections.mysql.database');
            $key = "Tables_in_{$dbName}";

            foreach ($tables as $t) {
                $tableName = $t->$key ?? array_values((array)$t)[0];

                // Create table syntax
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createSql = $createTable[0]->{'Create Table'} ?? null;

                if ($createSql) {
                    fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");
                    fwrite($handle, $createSql . ";\n\n");

                    // Rows
                    $rows = DB::table($tableName)->get();
                    if ($rows->isNotEmpty()) {
                        foreach ($rows as $row) {
                            $rowArray = (array)$row;
                            $columns = array_map(fn($col) => "`{$col}`", array_keys($rowArray));
                            $values = array_map(function ($val) {
                                if (is_null($val)) return "NULL";
                                return "'" . addslashes((string)$val) . "'";
                            }, array_values($rowArray));

                            fwrite($handle, "INSERT INTO `{$tableName}` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n");
                        }
                        fwrite($handle, "\n");
                    }
                }
            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
            fclose($handle);
        }, 200, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Restore Database dari file SQL
     */
    public function restoreDatabase(Request $request)
    {
        $request->validate([
            'sql_file' => ['required', 'file', 'mimes:sql,txt', 'max:51200'], // max 50MB
        ], [
            'sql_file.required' => 'Pilih file backup SQL.',
            'sql_file.mimes' => 'File harus berformat .sql.',
        ]);

        try {
            $path = $request->file('sql_file')->getRealPath();
            $sql = file_get_contents($path);

            DB::unprepared($sql);

            ActivityLog::log('restore_database', "Admin " . Auth::user()->name . " melakukan restore database dari file.");

            return back()->with('success', 'Database berhasil dipulihkan dari file backup.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memulihkan database: ' . $e->getMessage());
        }
    }
}
