<?php

namespace App\Support\Version\Contracts;

interface VersionManagerContract
{
    public function productName(): string;

    public function originalName(): string;

    public function version(): string;

    public function release(): string;

    public function codename(): string;

    public function build(): string;

    public function status(): string;

    public function isStable(): bool;

    public function isBeta(): bool;

    public function isAlpha(): bool;

    public function isReleaseCandidate(): bool;

    public function isPreRelease(): bool;

    public function license(): string;

    public function copyright(): string;

    public function description(): ?string;

    public function website(): ?string;

    public function organizationName(): ?string;

    public function organizationEmail(): ?string;

    public function organizationPhone(): ?string;

    public function organizationAddress(): ?string;
}
