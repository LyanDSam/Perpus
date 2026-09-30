<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Perpustakaan
        User::firstOrCreate(
            ['email' => 'admin@perpus.test'],
            [
                'name'     => 'Administrator Perpustakaan',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Data Koleksi Buku Realistis (12 buku)
        $daftarBuku = [
            [
                'kode_buku'    => 'BK-001',
                'judul'        => 'Laskar Pelangi',
                'pengarang'    => 'Andrea Hirata',
                'penerbit'     => 'Bentang Pustaka',
                'tahun_terbit' => 2005,
                'stok'         => 4,
            ],
            [
                'kode_buku'    => 'BK-002',
                'judul'        => 'Bumi Manusia',
                'pengarang'    => 'Pramoedya Ananta Toer',
                'penerbit'     => 'Hasta Mitra',
                'tahun_terbit' => 2005,
                'stok'         => 3,
            ],
            [
                'kode_buku'    => 'BK-003',
                'judul'        => 'Pemrograman Web dengan PHP & MySQL',
                'pengarang'    => 'Budi Raharjo',
                'penerbit'     => 'Informatika Bandung',
                'tahun_terbit' => 2021,
                'stok'         => 5,
            ],
            [
                'kode_buku'    => 'BK-004',
                'judul'        => 'Belajar Dasar Pemrograman JavaScript',
                'pengarang'    => 'Dicoding Academy',
                'penerbit'     => 'Dicoding Press',
                'tahun_terbit' => 2022,
                'stok'         => 6,
            ],
            [
                'kode_buku'    => 'BK-005',
                'judul'        => 'Rekayasa Perangkat Lunak Terstruktur & Berorientasi Objek',
                'pengarang'    => 'Rosa A.S. & M. Shalahuddin',
                'penerbit'     => 'Informatika',
                'tahun_terbit' => 2018,
                'stok'         => 3,
            ],
            [
                'kode_buku'    => 'BK-006',
                'judul'        => 'Menguasai Framework Laravel untuk Pemula',
                'pengarang'    => 'Yudho Yudhanto',
                'penerbit'     => 'Elex Media Komputindo',
                'tahun_terbit' => 2024,
                'stok'         => 5,
            ],
            [
                'kode_buku'    => 'BK-007',
                'judul'        => 'Basis Data Relasional Modern',
                'pengarang'    => 'Fathansyah',
                'penerbit'     => 'Informatika Bandung',
                'tahun_terbit' => 2020,
                'stok'         => 4,
            ],
            [
                'kode_buku'    => 'BK-008',
                'judul'        => 'Cantik Itu Luka',
                'pengarang'    => 'Eka Kurniawan',
                'penerbit'     => 'Gramedia Pustaka Utama',
                'tahun_terbit' => 2002,
                'stok'         => 2,
            ],
            [
                'kode_buku'    => 'BK-009',
                'judul'        => 'Filosofi Teras',
                'pengarang'    => 'Henry Manampiring',
                'penerbit'     => 'Penerbit Buku Kompas',
                'tahun_terbit' => 2018,
                'stok'         => 5,
            ],
            [
                'kode_buku'    => 'BK-010',
                'judul'        => 'Negeri 5 Menara',
                'pengarang'    => 'A. Fuadi',
                'penerbit'     => 'Gramedia Pustaka Utama',
                'tahun_terbit' => 2009,
                'stok'         => 4,
            ],
            [
                'kode_buku'    => 'BK-011',
                'judul'        => 'Struktur Data dan Algoritma Dasar',
                'pengarang'    => 'Rinaldi Munir',
                'penerbit'     => 'Informatika',
                'tahun_terbit' => 2019,
                'stok'         => 3,
            ],
            [
                'kode_buku'    => 'BK-012',
                'judul'        => 'Jaringan Komputer Berbasis Mikrotik',
                'pengarang'    => 'Madcoms',
                'penerbit'     => 'Andi Offset',
                'tahun_terbit' => 2021,
                'stok'         => 0, // Sengaja stok 0 untuk pengujian batas stok
            ],
        ];

        $bukuCreated = [];
        foreach ($daftarBuku as $bukuData) {
            $bukuCreated[] = Buku::firstOrCreate(
                ['kode_buku' => $bukuData['kode_buku']],
                $bukuData
            );
        }

        // 3. Data Anggota Siswa Realistis (8 anggota)
        $daftarAnggota = [
            ['nis' => '20241001', 'nama' => 'Ahmad Rizky Pratama', 'kelas' => 'XII RPL 1'],
            ['nis' => '20241002', 'nama' => 'Siti Nurhaliza',      'kelas' => 'XII RPL 1'],
            ['nis' => '20241003', 'nama' => 'Budi Santoso',         'kelas' => 'XII RPL 2'],
            ['nis' => '20241004', 'nama' => 'Dewi Lestari',         'kelas' => 'XII RPL 2'],
            ['nis' => '20241005', 'nama' => 'Muhammad Fajar Sidik', 'kelas' => 'XII TKJ 1'],
            ['nis' => '20241006', 'nama' => 'Anisa Rahmawati',      'kelas' => 'XII TKJ 1'],
            ['nis' => '20241007', 'nama' => 'Kevin Sanjaya',        'kelas' => 'XII MM 1'],
            ['nis' => '20241008', 'nama' => 'Putri Ayu Wulandari',  'kelas' => 'XII MM 2'],
        ];

        $anggotaCreated = [];
        foreach ($daftarAnggota as $anggotaData) {
            $anggotaCreated[] = Anggota::firstOrCreate(
                ['nis' => $anggotaData['nis']],
                $anggotaData
            );
        }

        // 4. Data Transaksi Peminjaman Contoh (6 transaksi)
        if (Peminjaman::count() === 0) {
            $now = Carbon::now();

            // Transaksi 1: Sedang dipinjam (5 hari lalu)
            Peminjaman::create([
                'buku_id'        => $bukuCreated[0]->id,
                'anggota_id'     => $anggotaCreated[0]->id,
                'tanggal_pinjam' => $now->copy()->subDays(5)->toDateString(),
                'status'         => 'dipinjam',
            ]);
            $bukuCreated[0]->decrement('stok', 1);

            // Transaksi 2: Sedang dipinjam (2 hari lalu)
            Peminjaman::create([
                'buku_id'        => $bukuCreated[2]->id,
                'anggota_id'     => $anggotaCreated[1]->id,
                'tanggal_pinjam' => $now->copy()->subDays(2)->toDateString(),
                'status'         => 'dipinjam',
            ]);
            $bukuCreated[2]->decrement('stok', 1);

            // Transaksi 3: Sedang dipinjam (8 hari lalu)
            Peminjaman::create([
                'buku_id'        => $bukuCreated[4]->id,
                'anggota_id'     => $anggotaCreated[4]->id,
                'tanggal_pinjam' => $now->copy()->subDays(8)->toDateString(),
                'status'         => 'dipinjam',
            ]);
            $bukuCreated[4]->decrement('stok', 1);

            // Transaksi 4: Sudah dikembalikan
            Peminjaman::create([
                'buku_id'         => $bukuCreated[1]->id,
                'anggota_id'      => $anggotaCreated[2]->id,
                'tanggal_pinjam'  => $now->copy()->subDays(14)->toDateString(),
                'tanggal_kembali' => $now->copy()->subDays(7)->toDateString(),
                'status'          => 'kembali',
            ]);

            // Transaksi 5: Sudah dikembalikan
            Peminjaman::create([
                'buku_id'         => $bukuCreated[3]->id,
                'anggota_id'      => $anggotaCreated[3]->id,
                'tanggal_pinjam'  => $now->copy()->subDays(10)->toDateString(),
                'tanggal_kembali' => $now->copy()->subDays(3)->toDateString(),
                'status'          => 'kembali',
            ]);

            // Transaksi 6: Sudah dikembalikan
            Peminjaman::create([
                'buku_id'         => $bukuCreated[6]->id,
                'anggota_id'      => $anggotaCreated[5]->id,
                'tanggal_pinjam'  => $now->copy()->subDays(12)->toDateString(),
                'tanggal_kembali' => $now->copy()->subDays(4)->toDateString(),
                'status'          => 'kembali',
            ]);
        }
    }
}
