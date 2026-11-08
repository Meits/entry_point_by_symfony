<?php

declare(strict_types=1);

namespace App\System\View;

use Twig\Environment;

final class View implements IView
{
    public function __construct(private readonly Environment $twig)
    {
    }

    public function make(string $path, array $data = []): string
    {
        return $this->twig->render($path, $data);
    }
}
