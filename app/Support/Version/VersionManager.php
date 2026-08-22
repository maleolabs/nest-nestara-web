<?php

namespace App\Support\Version;

use App\Support\Version\Contracts\VersionManagerContract;
use App\Support\Version\DTO\VersionData;

final class VersionManager implements VersionManagerContract
{
    public function __construct(
        protected VersionData $data,
    ) {}

    public function productName(): string
    {
        return $this->data->productName;
    }

    public function originalName(): string
    {
        return $this->data->originalName;
    }

    public function version(): string
    {
        return $this->data->version;
    }

    public function release(): string
    {
        return $this->data->release;
    }

    public function codename(): string
    {
        return $this->data->codename;
    }

    public function build(): string
    {
        return $this->data->build;
    }

    public function status(): string
    {
        return $this->data->status;
    }

    public function isStable(): bool
    {
        return $this->status() === 'stable';
    }

    public function isBeta(): bool
    {
        return $this->status() === 'beta';
    }

    public function isAlpha(): bool
    {
        return $this->status() === 'alpha';
    }

    public function isReleaseCandidate(): bool
    {
        return $this->status() === 'release-candidate';
    }

    public function isPreRelease(): bool
    {
        return in_array(
            $this->status(),
            [
                'development',
                'alpha',
                'beta',
                'release-candidate',
            ],
            true,
        );
    }

    public function license(): string
    {
        return $this->data->license;
    }

    public function copyright(): string
    {
        return $this->data->copyright;
    }

    public function description(): ?string
    {
        return $this->data->description;
    }

    public function website(): ?string
    {
        return $this->data->website;
    }

    public function organizationName(): ?string
    {
        return $this->data->organizationName;
    }

    public function organizationEmail(): ?string
    {
        return $this->data->organizationEmail;
    }

    public function organizationPhone(): ?string
    {
        return $this->data->organizationPhone;
    }

    public function organizationAddress(): ?string
    {
        return $this->data->organizationAddress;
    }

    public function fullVersion(): string
    {
        return sprintf(
            '%s v%s %s',
            $this->productName(),
            $this->version(),
            $this->release(),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return get_object_vars($this->data);
    }
}
