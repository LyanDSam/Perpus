<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Catat Transaksi Peminjaman Baru
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Pilih siswa peminjam dan buku yang tersedia. Stok buku akan otomatis berkurang 1.
                </p>
            </div>
            <a href="{{ route('peminjaman.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-6 sm:p-8">
                <form action="{{ route('peminjaman.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Pilih Anggota Peminjam -->
                    <div>
                        <label for="anggota_id" class="block text-sm font-semibold text-gray-700 mb-1">
                            Nama Siswa (Anggota) <span class="text-red-500">*</span>
                        </label>
                        <select name="anggota_id" id="anggota_id"
                            class="w-full text-sm border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('anggota_id') border-red-300 ring-1 ring-red-300 @enderror">
                            <option value="">-- Pilih Siswa Peminjam --</option>
                            @foreach ($anggota as $item)
                                <option value="{{ $item->id }}" {{ old('anggota_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }} &mdash; NIS: {{ $item->nis }} (Kelas {{ $item->kelas }})
                                </option>
                            @endforeach
                        </select>
                        @error('anggota_id')
                            <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pilih Buku (Hanya Stok > 0) -->
                    <div>
                        <label for="buku_id" class="block text-sm font-semibold text-gray-700 mb-1">
                            Buku yang Dipinjam <span class="text-red-500">*</span>
                        </label>
                        <select name="buku_id" id="buku_id"
                            class="w-full text-sm border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('buku_id') border-red-300 ring-1 ring-red-300 @enderror">
                            <option value="">-- Pilih Buku yang Tersedia --</option>
                            @forelse ($buku as $item)
                                <option value="{{ $item->id }}" {{ old('buku_id') == $item->id ? 'selected' : '' }}>
                                    [{{ $item->kode_buku }}] {{ $item->judul }} &mdash; Stok Tersedia: {{ $item->stok }} eks
                                </option>
                            @empty
                                <option value="" disabled>Tidak ada buku yang memiliki stok saat ini</option>
                            @endforelse
                        </select>
                        @error('buku_id')
                            <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1.5">
                            * Daftar di atas hanya menampilkan buku yang memiliki stok lebih dari 0.
                        </p>
                    </div>

                    <!-- Tanggal Pinjam -->
                    <div>
                        <label for="tanggal_pinjam" class="block text-sm font-semibold text-gray-700 mb-1">
                            Tanggal Peminjaman <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                            value="{{ old('tanggal_pinjam', $tanggal_hari_ini) }}"
                            class="w-full sm:w-1/2 text-sm border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('tanggal_pinjam') border-red-300 ring-1 ring-red-300 @enderror">
                        @error('tanggal_pinjam')
                            <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('peminjaman.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-medium rounded-lg shadow-sm transition">
                            Simpan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
