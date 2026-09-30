<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    // Nama tabel tunggal sesuai spesifikasi modul
    protected $table = 'anggota';

    // Kolom yang dapat diisi mass assignment
    protected $fillable = [
        'nis',
        'nama',
        'kelas',
    ];

    /**
     * Relasi ke data transaksi peminjaman (1 anggota bisa meminjam berkali-kali)
     */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'anggota_id');
    }
}
