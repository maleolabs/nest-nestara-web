<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PasswordRequest;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\OcrKtpRequest;
use App\Models\Pegawai;
use App\Models\Pengaturan;
use App\Models\User;
use App\Models\Pemohon;
use App\Services\DomainServices\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    private UserService $userService;

    public function __construct(UserService $userSercvice)
    {
        $this->userService = $userSercvice;
    }

    public function updateProfil(UserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $authUser = Auth::guard('api')->user();
        if ($authUser->username != $data['username']) {
            $request->validate([
                'username' => ['unique:users']
            ]);
        }

        $authUser = Auth::guard('api')->user();
        $user = $this->userService->save($authUser->id, $data);
        return response()->json(new UserResource($user, Response::HTTP_OK));
    }

    public function updatePassword(PasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = Auth::user();
        if (!Hash::check($data['old_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan password baru',
                'errors' => [
                    'old_password' => ['password lama salah']
                ]
            ], Response::HTTP_BAD_REQUEST);
        }
        $this->userService->savePassword($user->id, $data['new_password']);
        return response()->json([
            'success' => true,
            'message' => 'Password di ubah'
        ], Response::HTTP_OK);
    }

    /**
     * Hapus akun user yang sedang login (hard delete).
     *
     * Strategi pembersihan per tabel yang punya FK ke users
     * (hasil audit seluruh migration; hanya 4 tabel yang RESTRICT):
     *
     * - pemohon      : TIDAK disentuh di sini. FK baru ON DELETE SET NULL
     *                  otomatis melepasnya saat users dihapus → baris pemohon
     *                  jadi yatim dan seluruh riwayat izin (izin.pemohon_id)
     *                  tetap tersimpan.
     * - pegawai      : data kelembagaan (nama/nip/jabatan) masih dipakai
     *                  riwayat izin sebagai penandatangan → JANGAN dihapus,
     *                  cukup lepas kaitannya (user_id = NULL).
     * - pengaturan   : preferensi pribadi user, tidak berguna tanpa akunnya
     *                  → hapus permanen.
     * - ocr_ktp_requests : hasil OCR sementara milik user (retensi M6)
     *                  → hapus baris + file KTP di storage (best-effort).
     *
     * Tabel morphs TANPA FK constraint (tidak bisa menggagalkan DELETE,
     * dibersihkan agar tidak menyisakan baris yatim):
     * - personal_access_tokens, model_has_roles, model_has_permissions (spatie).
     */
    public function deleteAccount(): JsonResponse
    {
        try {
            $authUser = Auth::guard('api')->user();
            $userId = $authUser->id;

            DB::transaction(function () use ($authUser, $userId) {
                // pegawai: lepas kaitan akun, data kelembagaan tetap hidup
                Pegawai::where('user_id', $userId)->update(['user_id' => null]);
                // Explicit cleanup keeps account deletion portable on SQLite and
                // older databases where FK ON DELETE SET NULL is absent.
                Pemohon::where('user_id', $userId)->update(['user_id' => null]);

                // pengaturan: preferensi pribadi ikut akun
                Pengaturan::where('user_id', $userId)->delete();

                // ocr_ktp_requests: hapus baris lalu file KTP-nya (best-effort);
                // pola penghapusan file sama dengan OcrController@deleteStoredFile
                $ocrFilePaths = OcrKtpRequest::where('user_id', $userId)->pluck('file_path');
                OcrKtpRequest::where('user_id', $userId)->delete();
                foreach ($ocrFilePaths as $path) {
                    try {
                        if (!empty($path))
                            Storage::delete($path);
                    } catch (Exception $e) {
                        Log::channel('app')->warning('Gagal menghapus file KTP milik akun terhapus', ['path' => $path]);
                    }
                }

                // foto profil: nama file acak unik per user (Str::random) sehingga
                // aman dihapus; tersimpan di storage/app/public/users
                // (lihat UserServiceImpl::USER_PHOTO_FOLDER_PATH). Best-effort.
                foreach (array_unique(array_filter([$authUser->foto, $authUser->default_foto])) as $foto) {
                    try {
                        Storage::disk('public')->delete('users/' . $foto);
                    } catch (Exception $e) {
                        Log::channel('app')->warning('Gagal menghapus foto profil milik akun terhapus', ['foto' => $foto]);
                    }
                }

                // hygiene baris morphs tanpa FK constraint
                DB::table('personal_access_tokens')
                    ->where('tokenable_type', User::class)
                    ->where('tokenable_id', $userId)
                    ->delete();

                $permissionTables = config('permission.table_names');
                $morphKey = config('permission.column_names.model_morph_key');
                foreach (['model_has_roles', 'model_has_permissions'] as $pivotTable) {
                    DB::table($permissionTables[$pivotTable])
                        ->where('model_type', User::class)
                        ->where($morphKey, $userId)
                        ->delete();
                }

                // hard delete (bypass SoftDeletes): FK pemohon.user_id SET NULL
                // otomatis, username/email bebas untuk registrasi ulang
                $authUser->forceDelete();
            });

            // matikan token JWT aktif; force = blacklist permanen.
            // Dibungkus try/catch: kalau blacklist gagal, akun sudah terhapus
            // dan token tetap mati karena user tidak ada lagi — jangan balas 500.
            try {
                Auth::guard('api')->invalidate(true);
            } catch (Exception $e) {
                Log::channel('app')->warning('Gagal meng-invalidate token saat hapus akun', ['error' => $e->getMessage()]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Akun berhasil dihapus. Data permohonan Anda tetap tersimpan.'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::channel('app')->error('Tidak dapat menghapus akun', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus akun'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
