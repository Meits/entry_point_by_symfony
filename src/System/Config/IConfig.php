<?php

declare(strict_types=1);

namespace App\System\Config;

interface IConfig
{
    public function get(string $key): mixed;

    public function addConfig(string $file): void;
}
