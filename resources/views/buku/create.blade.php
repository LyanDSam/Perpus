<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Tambah Buku Baru
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Masukkan detail informasi buku baru ke katalog perpustakaan.
                </p>
            </div>
            <a href="{{ route('buku.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-6 sm:p-8">
                <form action="{{ route('buku.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Kode Buku -->
                    <div>
                        <label for="kode_buku" class="block text-sm font-semibold text-gray-700 mb-1">
                            Kode Buku <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode_buku" id="kode_buku" value="{{ old('kode_buku') }}"
                            placeholder="Contoh: BK-001"
                            class="w-full text-sm border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('kode_buku') border-red-300 ring-1 ring-red-300 @enderror">
                        @error('kode_buku')
                            <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Judul Buku -->
                    <div>
                        <label for="judul" class="block text-sm font-semibold text-gray-700 mb-1">
                            Judul Buku <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul') }}"
                            placeholder="Masukkan judul lengkap buku..."
                            class="w-full text-sm border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('judul') border-red-300 ring-1 ring-red-300 @enderror">
                        @error('judul')
                            <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pengarang & Penerbit (2 Kolom) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="pengarang" class="block text-sm font-semibold text-gray-700 mb-1">
                                Pengarang <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="pengarang" id="pengarang" value="{{ old('pengarang') }}"
                                placeholder="Nama pengarang / penulis..."
                                class="w-full text-sm border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('pengarang') border-red-300 ring-1 ring-red-300 @enderror">
                            @error('pengarang')
                                <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="penerbit" class="block text-sm font-semibold text-gray-700 mb-1">
                                Penerbit <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="penerbit" id="penerbit" value="{{ old('penerbit') }}"
                                placeholder="Nama penerbit..."
                                class="w-full text-sm border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('penerbit') border-red-300 ring-1 ring-red-300 @enderror">
                            @error('penerbit')
                                <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tahun Terbit & Stok (2 Kolom) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="tahun_terbit" class="block text-sm font-semibold text-gray-700 mb-1">
                                Tahun Terbit <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="tahun_terbit" id="tahun_terbit" value="{{ old('tahun_terbit', date('Y')) }}"
                                min="1900" max="{{ date('Y') + 1 }}" placeholder="Contoh: 2023"
                                class="w-full text-sm border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('tahun_terbit') border-red-300 ring-1 ring-red-300 @enderror">
                            @error('tahun_terbit')
                                <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="stok" class="block text-sm font-semibold text-gray-700 mb-1">
                                Jumlah Stok (Eksemplar) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="stok" id="stok" value="{{ old('stok', 1) }}" min="0"
                                placeholder="Jumlah buku yang tersedia..."
                                class="w-full text-sm border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('stok') border-red-300 ring-1 ring-red-300 @enderror">
                            @error('stok')
                                <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('buku.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-medium rounded-lg shadow-sm transition">
                            Simpan Buku
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
