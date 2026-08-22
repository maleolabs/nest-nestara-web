<?php

namespace App\Support\Version\Facades;

use App\Support\Version\VersionManager;
use Illuminate\Support\Facades\Facade;

final class Version extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return VersionManager::class;
    }
}
