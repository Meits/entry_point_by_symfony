<?php

declare(strict_types=1);

namespace App\Http;

use App\Entities\Book;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PageController extends Controller
{
    #[Route('/page/{alias}', name: 'page', methods: ['GET'])]
    public function show(string $alias = 'hello'): Response
    {
        /** @var Book[] $books */
        $books = $this->entityManager()->getRepository(Book::class)->findAll();

        return $this->render('page.html.twig', [
            'alias' => $alias,
            'books' => $books,
        ]);
    }
}
