<?php

namespace App\Support\Version\DTO;

use App\Support\Version\Exceptions\InvalidVersionFileException;

final readonly class VersionData
{
    private const REQUIRED_KEYS = [
        'PRODUCT_NAME',
        'ORIGINAL_NAME',
        'VERSION',
        'RELEASE',
        'CODENAME',
        'BUILD',
        'STATUS',
        'LICENSE',
        'COPYRIGHT',
        'RELEASE_DATE',
    ];

    public function __construct(
        public string $productName,
        public string $originalName,
        public string $version,
        public string $release,
        public string $codename,
        public string $build,
        public string $releaseDate,
        public string $status,
        public string $license,
        public string $copyright,
        public ?string $description = null,
        public ?string $website = null,
        public ?string $organizationName = null,
        public ?string $organizationEmail = null,
        public ?string $organizationPhone = null,
        public ?string $organizationAddress = null,
        public ?string $minimumPhpVersion = null,
        public ?string $minimumLaravelVersion = null,
        public ?string $minimumNodeVersion = null,
    ) {}

    /**
     * Build DTO from a parsed KEY=VALUE map. Unknown keys are ignored.
     *
     * @param  array<string, string>  $data
     *
     * @throws InvalidVersionFileException
     */
    public static function fromParsed(array $data): self
    {
        $missing = array_values(array_diff(self::REQUIRED_KEYS, array_keys($data)));

        if ($missing !== []) {
            throw new InvalidVersionFileException(sprintf(
                'Invalid VERSION file: missing required key(s): %s.',
                implode(', ', $missing),
            ));
        }

        return new self(
            productName: $data['PRODUCT_NAME'],
            originalName: $data['ORIGINAL_NAME'],
            version: $data['VERSION'],
            release: $data['RELEASE'],
            codename: $data['CODENAME'],
            build: $data['BUILD'],
            releaseDate: $data['RELEASE_DATE'],
            status: $data['STATUS'],
            license: $data['LICENSE'],
            copyright: $data['COPYRIGHT'],
            description: $data['DESCRIPTION'] ?? null,
            website: $data['WEBSITE'] ?? null,
            organizationName: $data['ORGANIZATION_NAME'] ?? null,
            organizationEmail: $data['ORGANIZATION_EMAIL'] ?? null,
            organizationPhone: $data['ORGANIZATION_PHONE'] ?? null,
            organizationAddress: $data['ORGANIZATION_ADDRESS'] ?? null,
            minimumPhpVersion: $data['MINIMUM_PHP_VERSION'] ?? null,
            minimumLaravelVersion: $data['MINIMUM_LARAVEL_VERSION'] ?? null,
            minimumNodeVersion: $data['MINIMUM_NODE_VERSION'] ?? null,
        );
    }

    /**
     * Safe defaults used when the VERSION file is absent, so the app still boots.
     */
    public static function fallback(): self
    {
        return new self(
            productName: 'Nestara',
            originalName: 'Nestara',
            version: '0.0.0',
            release: 'unknown',
            codename: 'unknown',
            build: '0',
            releaseDate: 'unknown',
            status: 'unknown',
            license: 'unknown',
            copyright: '',
        );
    }
}
