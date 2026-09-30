<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard ringkasan statistik perpustakaan.
     */
    public function index()
    {
        $totalBuku = Buku::count();
        $totalAnggota = Anggota::count();
        $bukuSedangDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $totalPeminjaman = Peminjaman::count();

        // 5 transaksi peminjaman terbaru untuk cuplikan aktivitas
        $peminjamanTerbaru = Peminjaman::with(['buku', 'anggota'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalBuku',
            'totalAnggota',
            'bukuSedangDipinjam',
            'totalPeminjaman',
            'peminjamanTerbaru'
        ));
    }
}
