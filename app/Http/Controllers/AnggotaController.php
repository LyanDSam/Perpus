<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnggotaController extends Controller
{
    /**
     * Menampilkan daftar anggota perpustakaan dengan pencarian dan paginasi.
     */
    public function index(Request $request)
    {
        $cari = $request->query('cari');

        $anggota = Anggota::when($cari, function ($query, $cari) {
            return $query->where('nama', 'like', "%{$cari}%")
                         ->orWhere('nis', 'like', "%{$cari}%")
                         ->orWhere('kelas', 'like', "%{$cari}%");
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view('anggota.index', compact('anggota', 'cari'));
    }

    /**
     * Menampilkan form tambah anggota baru.
     */
    public function create()
    {
        return view('anggota.create');
    }

    /**
     * Menyimpan data anggota baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis'   => 'required|string|max:20|unique:anggota,nis',
            'nama'  => 'required|string|max:100',
            'kelas' => 'required|string|max:20',
        ], [
            'nis.required'   => 'NIS anggota wajib diisi.',
            'nis.unique'     => 'NIS sudah terdaftar, silakan periksa kembali.',
            'nis.max'        => 'NIS maksimal 20 karakter.',
            'nama.required'  => 'Nama anggota wajib diisi.',
            'nama.max'       => 'Nama maksimal 100 karakter.',
            'kelas.required' => 'Kelas anggota wajib diisi.',
            'kelas.max'      => 'Kelas maksimal 20 karakter.',
        ]);

        Anggota::create($validated);

        return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit data anggota.
     */
    public function edit(Anggota $anggota)
    {
        return view('anggota.edit', compact('anggota'));
    }

    /**
     * Memperbarui data anggota di database.
     */
    public function update(Request $request, Anggota $anggota)
    {
        $validated = $request->validate([
            'nis'   => [
                'required',
                'string',
                'max:20',
                Rule::unique('anggota', 'nis')->ignore($anggota->id),
            ],
            'nama'  => 'required|string|max:100',
            'kelas' => 'required|string|max:20',
        ], [
            'nis.required'   => 'NIS anggota wajib diisi.',
            'nis.unique'     => 'NIS sudah digunakan oleh anggota lain.',
            'nis.max'        => 'NIS maksimal 20 karakter.',
            'nama.required'  => 'Nama anggota wajib diisi.',
            'nama.max'       => 'Nama maksimal 100 karakter.',
            'kelas.required' => 'Kelas anggota wajib diisi.',
            'kelas.max'      => 'Kelas maksimal 20 karakter.',
        ]);

        $anggota->update($validated);

        return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    /**
     * Menghapus anggota dengan perlindungan integritas relasi peminjaman.
     */
    public function destroy(Anggota $anggota)
    {
        // Cegah penghapusan jika anggota masih memiliki transaksi peminjaman
        if ($anggota->peminjaman()->exists()) {
            return redirect()->route('anggota.index')->with('error', 'Anggota ini tidak dapat dihapus karena masih memiliki riwayat data peminjaman.');
        }

        $anggota->delete();

        return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil dihapus.');
    }
}
