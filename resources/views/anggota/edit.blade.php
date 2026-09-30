<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Edit Data Anggota
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Perbarui data identitas anggota: <span class="font-semibold text-indigo-600">{{ $anggota->nama }}</span>
                </p>
            </div>
            <a href="{{ route('anggota.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-6 sm:p-8">
                <form action="{{ route('anggota.update', $anggota->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- NIS -->
                    <div>
                        <label for="nis" class="block text-sm font-semibold text-gray-700 mb-1">
                            Nomor Induk Siswa (NIS) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nis" id="nis" value="{{ old('nis', $anggota->nis) }}"
                            placeholder="Contoh: 20241001"
                            class="w-full text-sm border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('nis') border-red-300 ring-1 ring-red-300 @enderror">
                        @error('nis')
                            <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">
                            Nama Lengkap Siswa <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $anggota->nama) }}"
                            placeholder="Masukkan nama lengkap siswa..."
                            class="w-full text-sm border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('nama') border-red-300 ring-1 ring-red-300 @enderror">
                        @error('nama')
                            <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kelas -->
                    <div>
                        <label for="kelas" class="block text-sm font-semibold text-gray-700 mb-1">
                            Kelas / Jurusan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kelas" id="kelas" value="{{ old('kelas', $anggota->kelas) }}"
                            placeholder="Contoh: XII RPL 1, XI TKJ 2, X MM"
                            class="w-full text-sm border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 @error('kelas') border-red-300 ring-1 ring-red-300 @enderror">
                        @error('kelas')
                            <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('anggota.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-medium rounded-lg shadow-sm transition">
                            Perbarui Data Anggota
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
