<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Menampilkan laporan daftar buku yang sedang dipinjam dengan opsi cetak.
     */
    public function dipinjam()
    {
        $peminjaman = Peminjaman::with(['buku', 'anggota'])
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_pinjam', 'asc')
            ->get();

        return view('laporan.dipinjam', compact('peminjaman'));
    }
}
