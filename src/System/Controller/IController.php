<?php

declare(strict_types=1);

namespace App\System\Controller;

use Symfony\Component\HttpFoundation\Response;

interface IController
{
    /**
     * @param array<string, mixed> $data
     */
    public function render(string $path, array $data = []): Response;
}
