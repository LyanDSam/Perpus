<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar buku dengan fitur pencarian dan paginasi.
     */
    public function index(Request $request)
    {
        $cari = $request->query('cari');

        $buku = Buku::when($cari, function ($query, $cari) {
            return $query->where('judul', 'like', "%{$cari}%")
                         ->orWhere('pengarang', 'like', "%{$cari}%")
                         ->orWhere('kode_buku', 'like', "%{$cari}%");
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view('buku.index', compact('buku', 'cari'));
    }

    /**
     * Menampilkan formulir tambah buku baru.
     */
    public function create()
    {
        return view('buku.create');
    }

    /**
     * Menyimpan data buku baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_buku'    => 'required|string|max:20|unique:buku,kode_buku',
            'judul'        => 'required|string|max:150',
            'pengarang'    => 'required|string|max:100',
            'penerbit'     => 'required|string|max:100',
            'tahun_terbit' => 'required|integer|digits:4|min:1900|max:' . (date('Y') + 1),
            'stok'         => 'required|integer|min:0',
        ], [
            'kode_buku.required'    => 'Kode buku wajib diisi.',
            'kode_buku.unique'      => 'Kode buku sudah digunakan, gunakan kode lain.',
            'kode_buku.max'         => 'Kode buku maksimal 20 karakter.',
            'judul.required'        => 'Judul buku wajib diisi.',
            'judul.max'             => 'Judul buku maksimal 150 karakter.',
            'pengarang.required'    => 'Pengarang wajib diisi.',
            'pengarang.max'         => 'Pengarang maksimal 100 karakter.',
            'penerbit.required'     => 'Penerbit wajib diisi.',
            'penerbit.max'          => 'Penerbit maksimal 100 karakter.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.digits'   => 'Tahun terbit harus berupa 4 digit tahun (contoh: 2024).',
            'tahun_terbit.min'      => 'Tahun terbit minimal tahun 1900.',
            'stok.required'         => 'Stok buku wajib diisi.',
            'stok.integer'          => 'Stok buku harus berupa angka bulat.',
            'stok.min'              => 'Stok buku tidak boleh kurang dari 0.',
        ]);

        Buku::create($validated);

        return redirect()->route('buku.index')->with('success', 'Buku baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir edit buku.
     */
    public function edit(Buku $buku)
    {
        return view('buku.edit', compact('buku'));
    }

    /**
     * Memperbarui data buku di database.
     */
    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'kode_buku'    => [
                'required',
                'string',
                'max:20',
                Rule::unique('buku', 'kode_buku')->ignore($buku->id),
            ],
            'judul'        => 'required|string|max:150',
            'pengarang'    => 'required|string|max:100',
            'penerbit'     => 'required|string|max:100',
            'tahun_terbit' => 'required|integer|digits:4|min:1900|max:' . (date('Y') + 1),
            'stok'         => 'required|integer|min:0',
        ], [
            'kode_buku.required'    => 'Kode buku wajib diisi.',
            'kode_buku.unique'      => 'Kode buku sudah digunakan oleh buku lain.',
            'kode_buku.max'         => 'Kode buku maksimal 20 karakter.',
            'judul.required'        => 'Judul buku wajib diisi.',
            'judul.max'             => 'Judul buku maksimal 150 karakter.',
            'pengarang.required'    => 'Pengarang wajib diisi.',
            'pengarang.max'         => 'Pengarang maksimal 100 karakter.',
            'penerbit.required'     => 'Penerbit wajib diisi.',
            'penerbit.max'          => 'Penerbit maksimal 100 karakter.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.digits'   => 'Tahun terbit harus berupa 4 digit tahun (contoh: 2024).',
            'tahun_terbit.min'      => 'Tahun terbit minimal tahun 1900.',
            'stok.required'         => 'Stok buku wajib diisi.',
            'stok.integer'          => 'Stok buku harus berupa angka bulat.',
            'stok.min'              => 'Stok buku tidak boleh kurang dari 0.',
        ]);

        $buku->update($validated);

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    /**
     * Menghapus data buku dengan perlindungan integritas relasi peminjaman.
     */
    public function destroy(Buku $buku)
    {
        // Cek apakah buku memiliki riwayat atau sedang dipinjam
        if ($buku->peminjaman()->exists()) {
            return redirect()->route('buku.index')->with('error', 'Buku ini tidak dapat dihapus karena masih memiliki riwayat data peminjaman.');
        }

        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}
