<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /**
     * Menampilkan daftar transaksi peminjaman dengan filter status dan pencegahan N+1 query.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        $peminjaman = Peminjaman::with(['buku', 'anggota'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('peminjaman.index', compact('peminjaman', 'status'));
    }

    /**
     * Menampilkan formulir peminjaman buku (hanya menampilkan buku dengan stok > 0).
     */
    public function create()
    {
        $buku = Buku::where('stok', '>', 0)->orderBy('judul')->get();
        $anggota = Anggota::orderBy('nama')->get();
        $tanggal_hari_ini = now()->toDateString();

        return view('peminjaman.create', compact('buku', 'anggota', 'tanggal_hari_ini'));
    }

    /**
     * Menyimpan transaksi peminjaman baru dan mengurangi stok buku dalam DB Transaction.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'buku_id'        => 'required|exists:buku,id',
            'anggota_id'     => 'required|exists:anggota,id',
            'tanggal_pinjam' => 'required|date',
        ], [
            'buku_id.required'        => 'Buku wajib dipilih.',
            'buku_id.exists'          => 'Buku yang dipilih tidak valid.',
            'anggota_id.required'     => 'Anggota wajib dipilih.',
            'anggota_id.exists'       => 'Anggota yang dipilih tidak valid.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tanggal_pinjam.date'     => 'Format tanggal pinjam tidak valid.',
        ]);

        return DB::transaction(function () use ($validated) {
            // Kunci baris buku untuk mencegah race condition (pessimistic locking)
            $buku = Buku::where('id', $validated['buku_id'])->lockForUpdate()->first();

            // Tolak jika stok buku habis
            if ($buku->stok <= 0) {
                return back()->withInput()->with('error', 'Stok buku "' . $buku->judul . '" sedang kosong (0), peminjaman tidak dapat diproses.');
            }

            // Kurangi stok buku 1
            $buku->decrement('stok', 1);

            // Simpan catatan transaksi peminjaman
            Peminjaman::create([
                'buku_id'        => $buku->id,
                'anggota_id'     => $validated['anggota_id'],
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'status'         => 'dipinjam',
            ]);

            return redirect()->route('peminjaman.index')->with('success', 'Transaksi peminjaman berhasil disimpan dan stok buku berkurang 1.');
        });
    }

    /**
     * Memproses pengembalian buku dan menambah stok buku dalam DB Transaction.
     */
    public function kembali(Peminjaman $peminjaman)
    {
        // Tolak jika buku sudah dikembalikan sebelumnya
        if ($peminjaman->status === 'kembali') {
            return redirect()->route('peminjaman.index')->with('error', 'Buku ini sudah berstatus dikembalikan sebelumnya.');
        }

        return DB::transaction(function () use ($peminjaman) {
            // Update status peminjaman menjadi kembali dan catat tanggal pengembalian
            $peminjaman->update([
                'status'          => 'kembali',
                'tanggal_kembali' => now()->toDateString(),
            ]);

            // Tambah stok buku 1
            $peminjaman->buku()->increment('stok', 1);

            return redirect()->route('peminjaman.index')->with('success', 'Buku berhasil dikembalikan dan stok buku bertambah 1.');
        });
    }
}
