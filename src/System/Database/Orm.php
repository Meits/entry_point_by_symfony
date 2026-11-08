<?php

declare(strict_types=1);

namespace App\System\Database;

use App\System\Config\IConfig;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

/**
 * Thin, lazy wrapper around a Doctrine EntityManager. The connection is only
 * opened the first time a controller actually asks for it, so pages that
 * don't touch the database never pay for a DB round trip.
 */
final class Orm
{
    private ?EntityManager $entityManager = null;

    public function __construct(
        private readonly IConfig $config,
        private readonly string $projectDir,
    ) {
    }

    public function getEntityManager(): EntityManager
    {
        return $this->entityManager ??= $this->createEntityManager();
    }

    private function createEntityManager(): EntityManager
    {
        $ormConfig = ORMSetup::createAttributeMetadataConfiguration(
            paths: [$this->projectDir . '/src/Entities'],
            isDevMode: (bool) ($this->config->get('app.debug') ?? false),
        );
        // PHP 8.4+ ships lazy objects natively; avoids depending on
        // symfony/var-exporter's (now removed) ProxyHelper::generateLazyGhost().
        $ormConfig->enableNativeLazyObjects(\PHP_VERSION_ID >= 80400);

        $connection = DriverManager::getConnection($this->connectionParams(), $ormConfig);

        return new EntityManager($connection, $ormConfig);
    }

    /**
     * @return array<string, mixed>
     */
    private function connectionParams(): array
    {
        $driver = (string) ($this->env('DB_DRIVER') ?? $this->config->get('database.driver') ?? 'pdo_sqlite');

        if (\str_contains($driver, 'sqlite')) {
            $path = $this->env('DB_PATH') ?? $this->config->get('database.path') ?? 'var/database.sqlite';

            return [
                'driver' => $driver,
                'path' => \str_starts_with($path, '/') ? $path : $this->projectDir . '/' . $path,
            ];
        }

        return [
            'driver' => $driver,
            'host' => $this->env('DB_HOST') ?? $this->config->get('database.host'),
            'dbname' => $this->env('DB_NAME') ?? $this->config->get('database.dbname'),
            'user' => $this->env('DB_USER') ?? $this->config->get('database.user'),
            'password' => $this->env('DB_PASSWORD') ?? $this->config->get('database.password'),
        ];
    }

    private function env(string $name): ?string
    {
        $value = \getenv($name);

        return $value === false ? null : $value;
    }
}
