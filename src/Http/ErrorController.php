<?php

declare(strict_types=1);

namespace App\Http;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Wired as the fallback controller for Symfony's ExceptionListener
 * (see App\System\App::buildContainer()); renders a friendly error page
 * instead of leaking a stack trace to the browser.
 */
final class ErrorController extends Controller
{
    public function show(\Throwable $exception): Response
    {
        $statusCode = $exception instanceof HttpExceptionInterface
            ? $exception->getStatusCode()
            : 500;

        $response = $this->render('error.html.twig', [
            'statusCode' => $statusCode,
            'message' => $statusCode === 404 ? 'Page not found.' : 'Something went wrong.',
        ]);
        $response->setStatusCode($statusCode);

        return $response;
    }
}
