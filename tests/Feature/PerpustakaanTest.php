<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerpustakaanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. Tamu (belum login) diarahkan ke halaman login saat membuka /buku.
     */
    public function test_tamu_belum_login_diarahkan_ke_login_saat_membuka_buku(): void
    {
        $response = $this->get('/buku');

        $response->assertRedirect('/login');
    }

    /**
     * 2. Admin dapat menambah buku yang valid, dan input yang tidak valid ditolak.
     */
    public function test_admin_dapat_menambah_buku_valid_dan_input_tidak_valid_ditolak(): void
    {
        $admin = User::factory()->create();

        // Uji input valid
        $response = $this->actingAs($admin)->post('/buku', [
            'kode_buku'    => 'BK-VALID-01',
            'judul'        => 'Buku Pemrograman Laravel',
            'pengarang'    => 'Penulis Hebat',
            'penerbit'     => 'Penerbit Utama',
            'tahun_terbit' => 2024,
            'stok'         => 7,
        ]);

        $response->assertRedirect(route('buku.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('buku', [
            'kode_buku' => 'BK-VALID-01',
            'judul'     => 'Buku Pemrograman Laravel',
            'stok'      => 7,
        ]);

        // Uji input tidak valid (kosong)
        $responseInvalid = $this->actingAs($admin)->post('/buku', []);
        $responseInvalid->assertSessionHasErrors([
            'kode_buku',
            'judul',
            'pengarang',
            'penerbit',
            'tahun_terbit',
            'stok',
        ]);
    }

    /**
     * 3. Kode buku duplikat ditolak oleh validasi.
     */
    public function test_kode_buku_duplikat_ditolak(): void
    {
        $admin = User::factory()->create();

        Buku::create([
            'kode_buku'    => 'BK-DUP-01',
            'judul'        => 'Buku Pertama',
            'pengarang'    => 'Pengarang A',
            'penerbit'     => 'Penerbit A',
            'tahun_terbit' => 2023,
            'stok'         => 3,
        ]);

        // Coba masukkan buku kedua dengan kode_buku yang sama
        $response = $this->actingAs($admin)->post('/buku', [
            'kode_buku'    => 'BK-DUP-01',
            'judul'        => 'Buku Kedua dengan Kode Sama',
            'pengarang'    => 'Pengarang B',
            'penerbit'     => 'Penerbit B',
            'tahun_terbit' => 2024,
            'stok'         => 2,
        ]);

        $response->assertSessionHasErrors('kode_buku');
    }

    /**
     * 4. Peminjaman mengurangi stok buku dan pengembalian menambah stok buku.
     */
    public function test_peminjaman_mengurangi_stok_dan_pengembalian_menambah_stok(): void
    {
        $admin = User::factory()->create();

        $buku = Buku::create([
            'kode_buku'    => 'BK-STOK-01',
            'judul'        => 'Buku Algoritma',
            'pengarang'    => 'Rinaldi',
            'penerbit'     => 'Informatika',
            'tahun_terbit' => 2022,
            'stok'         => 5,
        ]);

        $anggota = Anggota::create([
            'nis'   => '20249901',
            'nama'  => 'Siswa Uji Coba',
            'kelas' => 'XII RPL 1',
        ]);

        // 1. Simpan Transaksi Peminjaman
        $responsePinjam = $this->actingAs($admin)->post('/peminjaman', [
            'buku_id'        => $buku->id,
            'anggota_id'     => $anggota->id,
            'tanggal_pinjam' => now()->toDateString(),
        ]);

        $responsePinjam->assertRedirect(route('peminjaman.index'));
        $responsePinjam->assertSessionHas('success');

        // Pastikan stok berkurang menjadi 4
        $this->assertEquals(4, $buku->fresh()->stok);

        $peminjaman = Peminjaman::first();
        $this->assertNotNull($peminjaman);
        $this->assertEquals('dipinjam', $peminjaman->status);

        // 2. Proses Pengembalian Buku
        $responseKembali = $this->actingAs($admin)->patch("/peminjaman/{$peminjaman->id}/kembali");

        $responseKembali->assertRedirect(route('peminjaman.index'));
        $responseKembali->assertSessionHas('success');

        // Pastikan stok bertambah kembali menjadi 5
        $this->assertEquals(5, $buku->fresh()->stok);

        // Pastikan status menjadi kembali dan tanggal_kembali terisi
        $this->assertEquals('kembali', $peminjaman->fresh()->status);
        $this->assertNotNull($peminjaman->fresh()->tanggal_kembali);
    }

    /**
     * 5. Peminjaman ditolak saat stok buku bernilai 0.
     */
    public function test_peminjaman_ditolak_saat_stok_nol(): void
    {
        $admin = User::factory()->create();

        $bukuHabis = Buku::create([
            'kode_buku'    => 'BK-HABIS-01',
            'judul'        => 'Buku Habis Stok',
            'pengarang'    => 'Pengarang X',
            'penerbit'     => 'Penerbit X',
            'tahun_terbit' => 2021,
            'stok'         => 0,
        ]);

        $anggota = Anggota::create([
            'nis'   => '20249902',
            'nama'  => 'Siswa Kedua',
            'kelas' => 'XII TKJ 2',
        ]);

        $response = $this->actingAs($admin)->post('/peminjaman', [
            'buku_id'        => $bukuHabis->id,
            'anggota_id'     => $anggota->id,
            'tanggal_pinjam' => now()->toDateString(),
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(0, $bukuHabis->fresh()->stok);
        $this->assertDatabaseMissing('peminjaman', [
            'buku_id' => $bukuHabis->id,
        ]);
    }

    /**
     * 6. Buku yang memiliki riwayat data peminjaman tidak dapat dihapus.
     */
    public function test_buku_yang_punya_peminjaman_tidak_dapat_dihapus(): void
    {
        $admin = User::factory()->create();

        $buku = Buku::create([
            'kode_buku'    => 'BK-TERIKAT-01',
            'judul'        => 'Buku yang Pernah Dipinjam',
            'pengarang'    => 'Pengarang Y',
            'penerbit'     => 'Penerbit Y',
            'tahun_terbit' => 2020,
            'stok'         => 2,
        ]);

        $anggota = Anggota::create([
            'nis'   => '20249903',
            'nama'  => 'Siswa Ketiga',
            'kelas' => 'XII MM 1',
        ]);

        Peminjaman::create([
            'buku_id'        => $buku->id,
            'anggota_id'     => $anggota->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status'         => 'dipinjam',
        ]);

        // Coba hapus buku
        $response = $this->actingAs($admin)->delete("/buku/{$buku->id}");

        $response->assertRedirect(route('buku.index'));
        $response->assertSessionHas('error');

        // Pastikan buku masih tetap ada di database
        $this->assertDatabaseHas('buku', [
            'id' => $buku->id,
        ]);
    }

    /**
     * 7. Anggota yang memiliki riwayat peminjaman juga tidak dapat dihapus.
     */
    public function test_anggota_yang_punya_peminjaman_tidak_dapat_dihapus(): void
    {
        $admin = User::factory()->create();

        $buku = Buku::create([
            'kode_buku'    => 'BK-ANGGOTA-01',
            'judul'        => 'Buku Basis Data',
            'pengarang'    => 'Fathansyah',
            'penerbit'     => 'Informatika',
            'tahun_terbit' => 2021,
            'stok'         => 3,
        ]);

        $anggota = Anggota::create([
            'nis'   => '20249904',
            'nama'  => 'Siswa Keempat',
            'kelas' => 'XII RPL 2',
        ]);

        Peminjaman::create([
            'buku_id'        => $buku->id,
            'anggota_id'     => $anggota->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status'         => 'dipinjam',
        ]);

        $response = $this->actingAs($admin)->delete("/anggota/{$anggota->id}");

        $response->assertRedirect(route('anggota.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('anggota', [
            'id' => $anggota->id,
        ]);
    }
}
