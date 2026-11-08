<?php

declare(strict_types=1);

namespace App\Tests;

use App\System\Config\Config;
use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
{
    public function testReadsTopLevelSection(): void
    {
        $config = new Config(\dirname(__DIR__), ['app.yaml']);

        self::assertSame(['debug' => true], $config->get('app'));
    }

    public function testReadsNestedField(): void
    {
        $config = new Config(\dirname(__DIR__), ['app.yaml']);

        self::assertTrue($config->get('app.debug'));
    }

    public function testMissingKeyReturnsNull(): void
    {
        $config = new Config(\dirname(__DIR__), ['app.yaml']);

        self::assertNull($config->get('does_not_exist'));
        self::assertNull($config->get('app.does_not_exist'));
    }
}
