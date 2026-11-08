<?php

declare(strict_types=1);

namespace App\System;

use App\Http\ErrorController;
use App\System\Database\Orm;
use App\System\Routing\AttributeRouteLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ContainerControllerResolver;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\HttpKernel\EventListener\ResponseListener;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Loader\AttributeDirectoryLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

/**
 * Boots the application: assembles the service container, wires the
 * routing/exception/response kernel listeners and dispatches the request.
 *
 * This intentionally mirrors (a tiny slice of) what
 * `symfony/framework-bundle` does under the hood, without pulling it in.
 */
final class App
{
    private readonly ContainerBuilder $container;
    private bool $compiled = false;

    public function __construct(private readonly string $basePath)
    {
        $this->container = $this->buildContainer();
    }

    /**
     * Fetches a public service, compiling the container on first use.
     * Useful for CLI scripts (see bin/init-db.php) that need a service
     * without going through run()/handle().
     */
    public function get(string $id): object
    {
        if (!$this->compiled) {
            $this->container->compile();
            $this->compiled = true;
        }

        return $this->container->get($id);
    }

    public function getOrm(): Orm
    {
        return $this->get(Orm::class);
    }

    public function run(): void
    {
        /** @var HttpKernelInterface $kernel */
        $kernel = $this->get('app.kernel');

        $request = Request::createFromGlobals();
        $response = $kernel->handle($request);
        $response->send();

        if ($kernel instanceof HttpKernel) {
            $kernel->terminate($request, $response);
        }
    }

    private function buildContainer(): ContainerBuilder
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.project_dir', $this->basePath);

        $loader = new YamlFileLoader($container, new FileLocator($this->basePath . '/config'));
        $loader->load('services.yaml');

        $container->register('app.request_context', RequestContext::class);

        $container->register(UrlMatcher::class)
            ->setArguments([$this->loadRoutes(), new Reference('app.request_context')]);

        $container->register(RequestStack::class);

        $container->register(ContainerControllerResolver::class)
            ->setArguments([$container]);

        $container->register(ArgumentResolver::class);

        $container->register(RouterListener::class)
            ->setArguments([new Reference(UrlMatcher::class), new Reference(RequestStack::class)]);

        $container->register(ResponseListener::class)
            ->setArguments(['UTF-8']);

        $container->register(ErrorListener::class)
            ->setArguments([ErrorController::class . '::show']);

        $container->register(EventDispatcher::class)
            ->addMethodCall('addSubscriber', [new Reference(RouterListener::class)])
            ->addMethodCall('addSubscriber', [new Reference(ResponseListener::class)])
            ->addMethodCall('addSubscriber', [new Reference(ErrorListener::class)]);

        $container->register('app.kernel', HttpKernel::class)
            ->setArguments([
                new Reference(EventDispatcher::class),
                new Reference(ContainerControllerResolver::class),
                new Reference(RequestStack::class),
                new Reference(ArgumentResolver::class),
            ])
            ->setPublic(true);

        return $container;
    }

    private function loadRoutes(): RouteCollection
    {
        $fileLocator = new FileLocator([$this->basePath . '/src/Http']);
        $classLoader = new AttributeRouteLoader();
        $directoryLoader = new AttributeDirectoryLoader($fileLocator, $classLoader);

        return $directoryLoader->load($this->basePath . '/src/Http', 'attribute');
    }
}
