<?php

namespace App\Services\DomainServices\Impl;

use App\Models\User;
use App\Services\DomainServices\RoleService;
use App\Services\DomainServices\UserService;
use App\utils\libs\ImageGenerator;
use Illuminate\Support\Facades\Hash;

class UserServiceImpl implements UserService {

    private const USER_PHOTO_FOLDER_PATH = 'public/users';

    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function save(?int $user_id = null, array $user_data, ?string $role_name = null): ?User
    {
        $user = null;
        if ($user_id) {
            $user = $this->find($user_id);
            if (!$user)
                return null;
            $user->update($user_data);
            $user = $this->find($user_id);
        } else {
            $user_data['password'] = Hash::make($user_data['password']);
            $user_data['foto'] = ImageGenerator::letterImage(substr($user_data['name'], 0, 1), self::USER_PHOTO_FOLDER_PATH);
            $user = User::create($user_data);
        }
        $this->assignRoles($user->id, [$role_name]);
        return $user;
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

}