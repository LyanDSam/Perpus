<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Dashboard Perpustakaan
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Selamat datang kembali, <span class="font-semibold text-indigo-600">{{ Auth::user()->name }}</span>! Berikut ringkasan operasional perpustakaan hari ini.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('peminjaman.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 transition shadow-sm">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Pinjam Buku
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Grid Kartu Ringkasan Statistik -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Total Judul Buku -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 text-blue-600 rounded-lg p-3">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div class="ms-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Buku</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalBuku) }}</h3>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 text-xs">
                        <a href="{{ route('buku.index') }}" class="font-medium text-blue-600 hover:text-blue-800 flex items-center">
                            Kelola koleksi buku
                            <svg class="w-3.5 h-3.5 ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Total Anggota -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-emerald-100 text-emerald-600 rounded-lg p-3">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ms-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Anggota</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalAnggota) }}</h3>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 text-xs">
                        <a href="{{ route('anggota.index') }}" class="font-medium text-emerald-600 hover:text-emerald-800 flex items-center">
                            Kelola data siswa/anggota
                            <svg class="w-3.5 h-3.5 ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Buku Sedang Dipinjam -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-amber-100 text-amber-600 rounded-lg p-3">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ms-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sedang Dipinjam</p>
                            <h3 class="text-2xl font-bold text-amber-600 mt-1">{{ number_format($bukuSedangDipinjam) }}</h3>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 text-xs">
                        <a href="{{ route('peminjaman.index', ['status' => 'dipinjam']) }}" class="font-medium text-amber-600 hover:text-amber-800 flex items-center">
                            Lihat buku yang belum kembali
                            <svg class="w-3.5 h-3.5 ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Total Transaksi -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 text-purple-600 rounded-lg p-3">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ms-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Peminjaman</p>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalPeminjaman) }}</h3>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 text-xs">
                        <a href="{{ route('laporan.dipinjam') }}" class="font-medium text-purple-600 hover:text-purple-800 flex items-center">
                            Cetak rekap laporan
                            <svg class="w-3.5 h-3.5 ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bagian Aktivitas Peminjaman Terbaru -->
            <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">Peminjaman Terbaru</h3>
                        <p class="text-xs text-gray-500 mt-0.5">5 transaksi peminjaman terakhir yang tercatat dalam sistem</p>
                    </div>
                    <a href="{{ route('peminjaman.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                        Lihat Semua Transaksi &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3.5">Peminjam</th>
                                <th class="px-6 py-3.5">Judul Buku</th>
                                <th class="px-6 py-3.5">Tgl Pinjam</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($peminjamanTerbaru as $item)
                                <tr class="hover:bg-gray-50/75 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">{{ $item->anggota->nama ?? '-' }}</div>
                                        <div class="text-xs text-gray-400">NIS: {{ $item->anggota->nis ?? '-' }} • Kelas {{ $item->anggota->kelas ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $item->buku->judul ?? '-' }}</div>
                                        <div class="text-xs text-gray-400">Kode: {{ $item->buku->kode_buku ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($item->status === 'dipinjam')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                                <span class="w-1.5 h-1.5 me-1.5 rounded-full bg-amber-500"></span>
                                                Dipinjam
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                <span class="w-1.5 h-1.5 me-1.5 rounded-full bg-green-500"></span>
                                                Kembali
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($item->status === 'dipinjam')
                                            <form action="{{ route('peminjaman.kembali', $item->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded-md shadow-sm transition">
                                                    Kembalikan
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">
                                        Belum ada riwayat transaksi peminjaman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
