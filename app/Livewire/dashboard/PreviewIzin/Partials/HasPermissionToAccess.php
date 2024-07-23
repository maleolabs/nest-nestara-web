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
        if ($user->hasRole(FRONT_OFFICE_ROLE) && !in_array($this->last_izin_status->nama, $this->statusIzinPermitted(FRONT_OFFICE_ROLE))) {
            $this->permission_message = 'Permohonan izin ini sedang diproses oleh back office, silahkan menunggu proses selesai.';
            return false;
        } else if ($user->hasRole(BACK_OFFICE_ROLE) && !in_array($this->last_izin_status->nama, $this->statusIzinPermitted(BACK_OFFICE_ROLE))) {
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
            FRONT_OFFICE_ROLE => [
                PreviewFeatureEnum::TINJAUAN_PERMOHONAN
            ],
            BACK_OFFICE_ROLE => [
                PreviewFeatureEnum::ENTRI_DATA,
                PreviewFeatureEnum::TINJAUAN_LAPANGAN
            ],
            ADMIN_ROLE => PreviewFeatureEnum::all()
        ];
    }

    private function statusIzinPermitted($role): array
    {
        switch ($role) {
            case FRONT_OFFICE_ROLE:
                return [
                    StatusIzinEnum::DIDAFTARKAN,
                    StatusIzinEnum::DITINJAU,
                    StatusIzinEnum::REVISI_PERSYARATAN,
                ];
            case BACK_OFFICE_ROLE:
                return [
                    StatusIzinEnum::PROSES
                ];
            case ADMIN_ROLE:
                return StatusIzinEnum::all();
        }
    }

}