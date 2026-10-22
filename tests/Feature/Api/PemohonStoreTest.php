<?php

namespace Tests\Feature\Api;

use App\Models\Pemohon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;

class PemohonStoreTest extends TestCase
{

    private array $dataPemohon = [
        'sumber_identitas' => 'ktp',
        'nomor_id' => '7498473990001',
        'nama_pemohon' => 'dummy api',
        'no_telepon' => '08543873847',
        'provinsi' => 75,
        'kabupaten' => 7504,
        'kecamatan' => 7504030,
        'kelurahan' => 7504030023,
        'detail_alamat' => 'jalan mangga',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        // pola pembersihan ala test existing: truncate manual dengan FK check off
        Schema::disableForeignKeyConstraints();
        Pemohon::truncate();
        User::truncate();
        Schema::enableForeignKeyConstraints();
    }

    private function makeUser(string $username): User
    {
        return User::create([
            'name' => $username,
            'username' => $username,
            'password' => 'Password#123',
        ]);
    }

    /**
     * (a) Saat create, pemohon yatim (user_id NULL, milik akun lama yang
     * sudah dihapus) dengan nomor_id sama harus DIADOPSI, bukan dibuat baru.
     */
    public function test_create_mengadopsi_pemohon_yatim_dengan_nomor_id_sama(): void
    {
        // pemohon yatim peninggalan akun yang sudah dihapus
        $orphan = Pemohon::create(array_merge($this->dataPemohon, [
            'nama_pemohon' => 'nama lama',
            'user_id' => null,
        ]));

        $user = $this->makeUser('pendaftar_adopt');

        $response = $this->withToken(JWTAuth::fromUser($user))->postJson('/api/pemohon', $this->dataPemohon);

        // adopsi → 200 (bukan 201); body hanya berisi id pemohon yatim
        $response->assertStatus(200);
        assertSame($orphan->id, (int) $response->getContent());

        // baris yang sama terklaim ke akun baru + datanya diperbarui
        $orphan->refresh();
        assertSame($user->id, $orphan->user_id);
        assertSame($this->dataPemohon['nama_pemohon'], $orphan->nama_pemohon);

        // tidak ada baris pemohon tambahan
        assertSame(1, Pemohon::count());
    }

    /**
     * (b) nomor_id milik user AKTIF lain → UNIQUE violation → 422
     * dengan bentuk errors konsisten validasi FormRequest.
     */
    public function test_create_ditolak_422_jika_nomor_id_milik_user_aktif_lain(): void
    {
        $pemilikLama = $this->makeUser('pemilik_lama');
        Pemohon::create(array_merge($this->dataPemohon, ['user_id' => $pemilikLama->id]));

        $pendaftarBaru = $this->makeUser('pendaftar_baru');

        $response = $this->withToken(JWTAuth::fromUser($pendaftarBaru))->postJson('/api/pemohon', $this->dataPemohon);

        $response->assertStatus(422)->assertJson([
            'success' => false,
            'errors' => [
                'nomor_id' => ['Nomor identitas sudah terdaftar pada akun lain.']
            ]
        ]);

        // pemohon milik pemilik lama tidak berubah tangan
        $pemilikLama->pemohon->refresh();
        assertSame($pemilikLama->id, $pemilikLama->pemohon->user_id);
        assertNotNull($pemilikLama->pemohon);
    }

}
