<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Transaksi Peminjaman Buku
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola sirkulasi peminjaman, pengembalian, dan penyesuaian stok buku secara otomatis.
                </p>
            </div>
            <div>
                <a href="{{ route('peminjaman.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-medium text-sm rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Catat Peminjaman Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <!-- Tab Filter Status -->
            <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center space-x-1 sm:space-x-2">
                    <a href="{{ route('peminjaman.index') }}"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition {{ empty($status) ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                        Semua Status
                    </a>
                    <a href="{{ route('peminjaman.index', ['status' => 'dipinjam']) }}"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition {{ $status === 'dipinjam' ? 'bg-amber-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                        Sedang Dipinjam
                    </a>
                    <a href="{{ route('peminjaman.index', ['status' => 'kembali']) }}"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition {{ $status === 'kembali' ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                        Sudah Dikembalikan
                    </a>
                </div>

                <div class="text-xs text-gray-500 font-medium">
                    Total: {{ $peminjaman->total() }} transaksi
                </div>
            </div>

            <!-- Tabel Data Peminjaman -->
            <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3.5 text-center w-16">No</th>
                                <th class="px-6 py-3.5">Peminjam (Siswa)</th>
                                <th class="px-6 py-3.5">Buku yang Dipinjam</th>
                                <th class="px-6 py-3.5">Tgl Pinjam</th>
                                <th class="px-6 py-3.5">Tgl Kembali</th>
                                <th class="px-6 py-3.5 text-center">Status</th>
                                <th class="px-6 py-3.5 text-center w-36">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($peminjaman as $index => $item)
                                <tr class="hover:bg-gray-50/75 transition">
                                    <td class="px-6 py-4 text-center text-xs text-gray-400 font-medium">
                                        {{ $peminjaman->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">{{ $item->anggota->nama ?? '-' }}</div>
                                        <div class="text-xs text-gray-400">NIS: {{ $item->anggota->nis ?? '-' }} • Kelas {{ $item->anggota->kelas ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $item->buku->judul ?? '-' }}</div>
                                        <div class="text-xs text-indigo-600 font-mono">Kode: {{ $item->buku->kode_buku ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-medium text-gray-700">
                                        {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        @if ($item->tanggal_kembali)
                                            <span class="text-emerald-700 font-medium">
                                                {{ \Carbon\Carbon::parse($item->tanggal_kembali)->translatedFormat('d M Y') }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($item->status === 'dipinjam')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                                <span class="w-1.5 h-1.5 me-1.5 rounded-full bg-amber-500"></span>
                                                Dipinjam
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                <span class="w-1.5 h-1.5 me-1.5 rounded-full bg-emerald-500"></span>
                                                Kembali
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($item->status === 'dipinjam')
                                            <form action="{{ route('peminjaman.kembali', $item->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian buku? Stok buku akan bertambah 1.')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                                                    <svg class="w-3.5 h-3.5 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Kembalikan
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400 font-medium italic">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <p class="font-medium text-gray-500">Tidak ada riwayat transaksi peminjaman.</p>
                                        @if($status)
                                            <p class="text-xs text-gray-400 mt-1">Tidak ada data untuk status "{{ $status }}".</p>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($peminjaman->hasPages())
                    <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
                        {{ $peminjaman->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
