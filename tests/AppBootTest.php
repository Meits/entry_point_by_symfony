<?php

declare(strict_types=1);

namespace App\Tests;

use App\System\App;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class AppBootTest extends TestCase
{
    public function testHomeRouteReturnsOk(): void
    {
        $app = new App(\dirname(__DIR__));

        /** @var HttpKernelInterface $kernel */
        $kernel = $app->get('app.kernel');
        $response = $kernel->handle(Request::create('/'));

        self::assertSame(200, $response->getStatusCode());
        self::assertStringContainsString('Entry Point', (string) $response->getContent());
    }

    public function testUnknownRouteReturns404(): void
    {
        $app = new App(\dirname(__DIR__));

        /** @var HttpKernelInterface $kernel */
        $kernel = $app->get('app.kernel');
        $response = $kernel->handle(Request::create('/no-such-route'));

        self::assertSame(404, $response->getStatusCode());
    }
}
