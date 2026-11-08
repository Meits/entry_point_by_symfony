<?php

declare(strict_types=1);

namespace App\Http;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends Controller
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function indexAction(): Response
    {
        return $this->render('index.html.twig', ['title' => 'Entry Point']);
    }
}
