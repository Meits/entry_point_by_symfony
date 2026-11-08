<?php

declare(strict_types=1);

namespace App\System\Config;

use Symfony\Component\Yaml\Yaml;

final class Config implements IConfig
{
    /** @var array<string, mixed> */
    private array $values = [];

    /**
     * @param string[] $files YAML file names, relative to "<projectDir>/config".
     */
    public function __construct(
        private readonly string $projectDir,
        array $files = [],
    ) {
        foreach ($files as $file) {
            $this->addConfig($file);
        }
    }

    public function addConfig(string $file): void
    {
        $path = $this->projectDir . '/config/' . $file;
        $parsed = Yaml::parseFile($path);

        if (\is_array($parsed)) {
            $this->values = [...$this->values, ...$parsed];
        }
    }

    public function get(string $key): mixed
    {
        [$section, $field] = \array_pad(\explode('.', $key, 2), 2, null);

        if (!isset($this->values[$section])) {
            return null;
        }

        if ($field === null) {
            return $this->values[$section];
        }

        return $this->values[$section][$field] ?? null;
    }
}
