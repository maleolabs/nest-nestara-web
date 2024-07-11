<?php

namespace App\Livewire\Dashboard\PreviewIzin\Partials;

use App\utils\enums\PreviewFeatureEnum;
use App\utils\enums\StatusIzinEnum;

trait HasPermissionToAccess
{

    public $permission_message;

    protected $has_permission = false;

    protected function hasPermission($feature): bool
    {
        $roles = auth()->user()->getRoleNames()->all();
        $roles_feature = array_intersect_key(array_flip($roles), $this->rolesFeatures());
        if (empty($roles_feature))
            return false;
        foreach (array_flip($roles_feature) as $role) {
            if (in_array($feature, $this->getFeatures($role))) 
                return true;
        }
        return false;
    }

    protected function canAccess(): bool
    {
        if (!$this->preview_izin) {
            $this->permission_message = 'Data tidak ditemukan!';
            return false;
        }
        $user = auth()->user();
        if ($user->hasRole('pengelola_layanan') && !in_array($this->last_izin_status->nama, $this->statusIzinPermitted('pengelola_layanan'))) {
            $this->permission_message = 'Permohonan izin ini sedang diproses oleh back office, silahkan menunggu proses selesai.';
            return false;
        } else if ($user->hasRole('pegawai') && !in_array($this->last_izin_status->nama, $this->statusIzinPermitted('pegawai'))) {
            $this->permission_message = 'Anda sudah tidak boleh mengubah data permohonan ini.';
        }
        return true;
    }

    private function getFeatures($role): array
    {
        return $this->rolesFeatures()[$role];
    }

    private function rolesFeatures(): array
    {
        return [
            'pengelola_layanan' => [
                PreviewFeatureEnum::TINJAUAN_PERMOHONAN
            ],
            'pegawai' => [
                PreviewFeatureEnum::ENTRI_DATA,
                PreviewFeatureEnum::TINJAUAN_LAPANGAN
            ],
            'admin' => PreviewFeatureEnum::all()
        ];
    }

    private function statusIzinPermitted($role): array
    {
        switch ($role) {
            case 'pengelola_layanan':
                return [
                    StatusIzinEnum::DIDAFTARKAN,
                    StatusIzinEnum::DITINJAU,
                    StatusIzinEnum::REVISI_PERSYARATAN,
                ];
            case 'pegawai':
                return [
                    StatusIzinEnum::PROSES
                ];
            case 'admin':
                return StatusIzinEnum::all();
        }
    }

}