<?php

namespace App\Support\Version;

use App\Support\Version\DTO\VersionData;
use App\Support\Version\Parsers\VersionParser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

final class VersionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(VersionManager::class, function () {
            $path = base_path('VERSION');

            if (! file_exists($path)) {
                Log::warning(sprintf(
                    'VERSION file not found at %s; falling back to default application metadata.',
                    $path,
                ));

                return new VersionManager(VersionData::fallback());
            }

            return new VersionManager(
                VersionData::fromParsed(
                    (new VersionParser)->parse($path),
                ),
            );
        });
    }
}
