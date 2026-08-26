<?php

namespace Tests\Feature\Api;

use App\Models\Izin;
use App\Models\JenisPerizinan;
use App\Models\Pemohon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;

class DeleteAccountTest extends TestCase
{

    private array $dataPemohon = [
        'sumber_identitas' => 'ktp',
        'nomor_id' => '7498473990002',
        'nama_pemohon' => 'dummy hapus akun',
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
        DB::statement("SET foreign_key_checks=0");
        Pemohon::truncate();
        User::truncate();
        Izin::truncate();
        JenisPerizinan::truncate();
        DB::statement("SET foreign_key_checks=1");
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
     * (c) deleteAccount: user terhapus hard, pemohon selamat dengan
     * user_id NULL (FK SET NULL), dan izin di baliknya tetap ada.
     */
    public function test_delete_account_melepas_pemohon_dan_menyelamatkan_izin(): void
    {
        $user = $this->makeUser('akun_dihapus');

        $jenisPerizinan = JenisPerizinan::create(['nama' => 'Izin Uji Hapus Akun']);
        $pemohon = Pemohon::create(array_merge($this->dataPemohon, ['user_id' => $user->id]));
        $izin = Izin::create([
            'jenis_perizinan_id' => $jenisPerizinan->id,
            'pemohon_id' => $pemohon->id,
            'no_pendaftaran' => 1,
        ]);

        $response = $this->actingAs($user, 'api')->deleteJson('/api/user/account');

        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Akun berhasil dihapus. Data permohonan Anda tetap tersimpan.'
        ]);

        // akun benar-benar terhapus (hard delete, bukan soft delete)
        assertNull(User::find($user->id));

        // pemohon selamat dan menjadi yatim (FK ON DELETE SET NULL)
        assertNotNull(Pemohon::find($pemohon->id));
        $pemohon->refresh();
        assertNull($pemohon->user_id);

        // riwayat izin tetap tersimpan
        assertNotNull(Izin::find($izin->id));
    }

}
