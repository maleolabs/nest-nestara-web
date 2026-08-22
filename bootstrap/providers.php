<?php

use App\Providers\AppServiceProvider;
use App\Providers\EventServiceProvider;
use App\Support\Version\VersionServiceProvider;
use Maatwebsite\Excel\ExcelServiceProvider;
use Tymon\JWTAuth\Providers\LaravelServiceProvider;

return [
    AppServiceProvider::class,
    VersionServiceProvider::class,
    EventServiceProvider::class,
    ExcelServiceProvider::class,
    LaravelServiceProvider::class,
];
