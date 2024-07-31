<?php

namespace App\Services\DomainServices\Impl;

use App\Models\User;
use App\Services\AppService\FileService;
use App\Services\DomainServices\RoleService;
use App\Services\DomainServices\UserService;
use App\utils\libs\ImageGenerator;
use Illuminate\Support\Facades\Hash;

class UserServiceImpl implements UserService {

    private const USER_PHOTO_FOLDER_PATH = 'public/users';

    protected RoleService $roleService;

    protected FileService $fileService;

    public function __construct(
        RoleService $roleService,
        FileService $fileService
    ) {
        $this->roleService = $roleService;
        $this->fileService = $fileService;
    }

    public function save(?int $user_id = null, array $user_data, ?string $role_name = null): ?User
    {
        $user = null;
        if ($user_id) {
            $user = $this->find($user_id);
            if (!$user)
                return $user;
            $user->update($user_data);
            $user = $this->find($user_id);
        } else {
            $user_data['password'] = Hash::make($user_data['password']);
            $user_foto = ImageGenerator::letterImage(substr($user_data['name'], 0, 1), self::USER_PHOTO_FOLDER_PATH);
            $user_data['foto'] = $user_foto;
            $user_data['default_foto'] = $user_foto;
            $user = User::create($user_data);
        }
        $this->assignRoles($user->id, [$role_name]);
        return $user;
    }

    public function saveFotoFromLivewire(int $user_id, string $original_path): void
    {
        $user = User::find($user_id);
        if (!$user) return;

        $file_name = $this->fileService->fileManager()->moveFile()
                    ->from($original_path)
                    ->toPublic('users')
                    ->moveWithNewName();

        $user->foto = $file_name;
        $user->save();
    }

    public function find(int $user_id): ?User
    {
        return User::find($user_id);
    }

    public function assignRoles(int $user_id, array $role_names): void
    {
        $user = $this->find($user_id);
        if (!$user)
            return;
        $roles = $this->roleService->getAllByNames($role_names);
        $user->assignRole($roles);
    }

    public function getAllByRole(string $role_name): array
    {
        $users = User::whereHas('roles', function ($roles) use ($role_name) {
            $roles->where('name', $role_name);
        })->orderBy('created_at', 'DESC')->get();

        return $users->all();
    }

    public function deleteFoto(int $user_id): void
    {
        $user = User::find($user_id);
        if (!$user) return;

        $user->foto = $user->default_foto;
        $user->save();
    }

}