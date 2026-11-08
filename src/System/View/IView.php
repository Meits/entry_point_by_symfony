<?php

declare(strict_types=1);

namespace App\System\View;

interface IView
{
    /**
     * @param array<string, mixed> $data
     */
    public function make(string $path, array $data = []): string;
}
