<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\PangkatGolongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PangkatGolonganController extends Controller
{
    public function index()
    {
        $pangkats = PangkatGolongan::withCount('gurus')
            ->orderBy('urutan')
            ->paginate(10)
            ->withQueryString();
        return view('admin.pangkat.index', compact('pangkats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'golongan' => ['required', 'string', 'max:20', 'unique:pangkat_golongans,golongan'],
            'pangkat' => ['required', 'string', 'max:100'],
            'urutan' => ['nullable', 'integer'],
        ], [
            'golongan.required' => 'Golongan wajib diisi.',
            'golongan.unique' => 'Golongan sudah ada.',
            'pangkat.required' => 'Pangkat wajib diisi.',
        ]);

        $item = PangkatGolongan::create([
            'golongan' => $validated['golongan'],
            'pangkat' => $validated['pangkat'],
            'urutan' => $validated['urutan'] ?? 99,
            'is_active' => true,
        ]);

        ActivityLog::log('create_pangkat', "Menambah master pangkat/golongan: {$item->label}", $item);

        return back()->with('success', 'Master Pangkat/Golongan berhasil ditambahkan.');
    }

    public function update(Request $request, PangkatGolongan $pangkatGolongan)
    {
        $validated = $request->validate([
            'golongan' => ['required', 'string', 'max:20', 'unique:pangkat_golongans,golongan,' . $pangkatGolongan->id],
            'pangkat' => ['required', 'string', 'max:100'],
            'urutan' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $pangkatGolongan->golongan = $validated['golongan'];
        $pangkatGolongan->pangkat = $validated['pangkat'];
        $pangkatGolongan->urutan = $validated['urutan'] ?? $pangkatGolongan->urutan;
        $pangkatGolongan->is_active = $request->has('is_active');
        $pangkatGolongan->save();

        ActivityLog::log('update_pangkat', "Memperbarui master pangkat/golongan: {$pangkatGolongan->label}", $pangkatGolongan);

        return back()->with('success', 'Master Pangkat/Golongan berhasil diperbarui.');
    }

    public function destroy(PangkatGolongan $pangkatGolongan)
    {
        if ($pangkatGolongan->gurus()->exists()) {
            return back()->with('error', 'Pangkat/Golongan tidak dapat dihapus karena sedang digunakan oleh data Guru.');
        }

        $label = $pangkatGolongan->label;
        $pangkatGolongan->delete();

        ActivityLog::log('delete_pangkat', "Menghapus master pangkat/golongan: {$label}");

        return back()->with('success', 'Master Pangkat/Golongan berhasil dihapus.');
    }
}
