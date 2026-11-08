<?php

declare(strict_types=1);

namespace App\Http;

use App\System\Controller\IController;
use App\System\Database\Orm;
use App\System\View\IView;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller implements IController
{
    public function __construct(
        private readonly IView $view,
        private readonly Orm $orm,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(string $path, array $data = []): Response
    {
        return new Response($this->view->make($path, $data));
    }

    protected function entityManager(): EntityManager
    {
        return $this->orm->getEntityManager();
    }
}
