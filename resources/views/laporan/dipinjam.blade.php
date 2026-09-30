<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 print:hidden">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Laporan Buku Sedang Dipinjam
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Daftar rekapitulasi buku yang sedang berada di tangan siswa dan belum dikembalikan.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="window.print()" class="inline-flex items-center px-4 py-2.5 bg-gray-900 hover:bg-black text-white font-medium text-sm rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak Laporan
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-6 sm:p-8 print:border-none print:shadow-none print:p-0">

                <!-- Kop Laporan (Terlihat Saat Cetak & Layar) -->
                <div class="text-center pb-6 mb-6 border-b border-gray-200">
                    <h1 class="text-xl font-bold uppercase tracking-wider text-gray-900">Perpustakaan SMK</h1>
                    <h2 class="text-lg font-semibold text-gray-700 mt-0.5">Laporan Buku Sedang Dipinjam</h2>
                    <p class="text-xs text-gray-500 mt-1">
                        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y - H:i') }} WIB • Oleh: {{ Auth::user()->name }}
                    </p>
                </div>

                <!-- Informasi Ringkasan -->
                <div class="mb-4 flex justify-between items-center text-xs text-gray-600 font-medium">
                    <div>
                        Status Laporan: <span class="text-amber-700 font-semibold uppercase">Sedang Dipinjam (Belum Kembali)</span>
                    </div>
                    <div>
                        Total Buku Belum Kembali: <span class="font-bold text-gray-900">{{ $peminjaman->count() }} Eksemplar</span>
                    </div>
                </div>

                <!-- Tabel Laporan -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-800 border-collapse border border-gray-300">
                        <thead class="bg-gray-100 text-xs uppercase font-semibold text-gray-700">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2.5 text-center w-12">No</th>
                                <th class="border border-gray-300 px-4 py-2.5">Kode Buku</th>
                                <th class="border border-gray-300 px-4 py-2.5">Judul Buku</th>
                                <th class="border border-gray-300 px-4 py-2.5">Nama Peminjam</th>
                                <th class="border border-gray-300 px-4 py-2.5 text-center">NIS</th>
                                <th class="border border-gray-300 px-4 py-2.5 text-center">Kelas</th>
                                <th class="border border-gray-300 px-4 py-2.5 text-center">Tgl Pinjam</th>
                                <th class="border border-gray-300 px-4 py-2.5 text-center">Lama Pinjam</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($peminjaman as $index => $item)
                                @php
                                    $tglPinjam = \Carbon\Carbon::parse($item->tanggal_pinjam);
                                    $lamaHari = (int) $tglPinjam->diffInDays(now());
                                @endphp
                                <tr class="hover:bg-gray-50/50">
                                    <td class="border border-gray-300 px-4 py-2.5 text-center text-xs">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2.5 font-mono text-xs font-semibold text-indigo-800">
                                        {{ $item->buku->kode_buku ?? '-' }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2.5 font-medium">
                                        {{ $item->buku->judul ?? '-' }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2.5">
                                        {{ $item->anggota->nama ?? '-' }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2.5 text-center text-xs font-mono">
                                        {{ $item->anggota->nis ?? '-' }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2.5 text-center text-xs">
                                        {{ $item->anggota->kelas ?? '-' }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2.5 text-center text-xs">
                                        {{ $tglPinjam->translatedFormat('d/m/Y') }}
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2.5 text-center text-xs font-semibold {{ $lamaHari > 7 ? 'text-rose-600' : 'text-gray-700' }}">
                                        {{ $lamaHari }} hari
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="border border-gray-300 px-4 py-8 text-center text-gray-400">
                                        Saat ini tidak ada buku yang sedang dipinjam. Seluruh koleksi buku tersedia di perpustakaan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Bagian Tanda Tangan Cetak -->
                <div class="mt-12 hidden print:grid grid-cols-2 gap-8 text-xs text-gray-700 pt-6">
                    <div>
                        <p>Mengetahui,</p>
                        <p class="font-semibold mt-0.5">Kepala Perpustakaan Sekolah</p>
                        <div class="h-20"></div>
                        <p class="font-bold underline">( ............................................ )</p>
                        <p>NIP. ........................................</p>
                    </div>
                    <div class="text-right">
                        <p>Petugas Administrasi,</p>
                        <div class="h-20 mt-4"></div>
                        <p class="font-bold underline">{{ Auth::user()->name }}</p>
                        <p>Admin Sistem Perpustakaan</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
