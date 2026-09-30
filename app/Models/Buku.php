<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    // Nama tabel tunggal sesuai spesifikasi
    protected $table = 'buku';

    // Kolom yang dapat diisi secara mass assignment
    protected $fillable = [
        'kode_buku',
        'judul',
        'pengarang',
        'penerbit',
        'tahun_terbit',
        'stok',
    ];

    /**
     * Relasi ke data peminjaman (1 buku bisa memiliki banyak transaksi peminjaman)
     */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }
}
