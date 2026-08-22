<?php

namespace Tests\Unit\Support\Version;

use App\Support\Version\DTO\VersionData;
use App\Support\Version\Exceptions\InvalidVersionFileException;
use App\Support\Version\VersionManager;
use PHPUnit\Framework\TestCase;

final class VersionManagerTest extends TestCase
{
    private function manager(): VersionManager
    {
        return new VersionManager(new VersionData(
            productName: 'Nestara',
            originalName: 'Nestara',
            version: '1.0.0',
            release: 'Genesis',
            codename: 'Genesis',
            build: '1',
            releaseDate: '2026-08-22',
            status: 'alpha',
            license: 'proprietary',
            copyright: 'MaleoLabs',
            website: 'https://maleolabs.com',
            organizationName: 'DPMPTSP Kabupaten Bone Bolango',
            organizationEmail: 'support@perizinanpmptsp.go.id',
            organizationPhone: '(021) 123-4567',
            organizationAddress: 'Kompleks Perkantoran Bone Bolango, Gorontalo',
            minimumPhpVersion: '8.4',
            minimumLaravelVersion: '13',
            minimumNodeVersion: '22',
        ));
    }

    public function test_getters_return_all_fields(): void
    {
        $manager = $this->manager();

        $this->assertSame('Nestara', $manager->productName());
        $this->assertSame('Nestara', $manager->originalName());
        $this->assertSame('1.0.0', $manager->version());
        $this->assertSame('Genesis', $manager->release());
        $this->assertSame('Genesis', $manager->codename());
        $this->assertSame('1', $manager->build());
        $this->assertSame('alpha', $manager->status());
        $this->assertSame('proprietary', $manager->license());
        $this->assertSame('MaleoLabs', $manager->copyright());
        $this->assertSame('https://maleolabs.com', $manager->website());
        $this->assertSame('DPMPTSP Kabupaten Bone Bolango', $manager->organizationName());
        $this->assertSame('support@perizinanpmptsp.go.id', $manager->organizationEmail());
        $this->assertSame('(021) 123-4567', $manager->organizationPhone());
        $this->assertSame('Kompleks Perkantoran Bone Bolango, Gorontalo', $manager->organizationAddress());
    }

    public function test_status_helpers(): void
    {
        $manager = $this->manager();

        $this->assertFalse($manager->isStable());
        $this->assertTrue($manager->isAlpha());
        $this->assertFalse($manager->isBeta());
        $this->assertFalse($manager->isReleaseCandidate());
        $this->assertTrue($manager->isPreRelease());

        $stable = new VersionManager(new VersionData(
            productName: 'Nestara',
            originalName: 'Nestara',
            version: '1.0.0',
            release: 'Genesis',
            codename: 'Genesis',
            build: '1',
            releaseDate: '2026-08-22',
            status: 'stable',
            license: 'proprietary',
            copyright: 'MaleoLabs',
        ));

        $this->assertTrue($stable->isStable());
        $this->assertFalse($stable->isPreRelease());
    }

    public function test_full_version_format(): void
    {
        $this->assertSame('Nestara v1.0.0 Genesis', $this->manager()->fullVersion());
    }

    public function test_all_returns_every_field(): void
    {
        $all = $this->manager()->all();

        $this->assertSame('Nestara', $all['productName']);
        $this->assertSame('1.0.0', $all['version']);
        $this->assertSame('2026-08-22', $all['releaseDate']);
        $this->assertSame('DPMPTSP Kabupaten Bone Bolango', $all['organizationName']);
        $this->assertCount(19, $all);
    }

    public function test_from_parsed_throws_listing_missing_required_keys(): void
    {
        try {
            VersionData::fromParsed([
                'PRODUCT_NAME' => 'Nestara',
                'VERSION' => '1.0.0',
                'UNKNOWN_KEY' => 'ignored',
            ]);

            $this->fail('Expected InvalidVersionFileException was not thrown.');
        } catch (InvalidVersionFileException $exception) {
            $message = $exception->getMessage();

            $this->assertStringContainsString('ORIGINAL_NAME', $message);
            $this->assertStringContainsString('RELEASE', $message);
            $this->assertStringContainsString('CODENAME', $message);
            $this->assertStringContainsString('BUILD', $message);
            $this->assertStringContainsString('STATUS', $message);
            $this->assertStringContainsString('LICENSE', $message);
            $this->assertStringContainsString('COPYRIGHT', $message);
            $this->assertStringContainsString('RELEASE_DATE', $message);
        }
    }

    public function test_fallback_provides_safe_defaults(): void
    {
        $manager = new VersionManager(VersionData::fallback());

        $this->assertSame('Nestara', $manager->productName());
        $this->assertSame('Nestara', $manager->originalName());
        $this->assertSame('0.0.0', $manager->version());
        $this->assertSame('unknown', $manager->release());
        $this->assertSame('unknown', $manager->codename());
        $this->assertSame('0', $manager->build());
        $this->assertSame('unknown', $manager->status());
        $this->assertSame('unknown', $manager->license());
        $this->assertSame('', $manager->copyright());
        $this->assertNull($manager->website());
        $this->assertNull($manager->organizationName());
        $this->assertNull($manager->organizationEmail());
        $this->assertNull($manager->organizationPhone());
        $this->assertNull($manager->organizationAddress());

        $all = $manager->all();

        $this->assertNull($all['website']);
        $this->assertNull($all['minimumPhpVersion']);
        $this->assertNull($all['minimumLaravelVersion']);
        $this->assertNull($all['minimumNodeVersion']);
    }
}
