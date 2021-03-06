<?php

namespace App\System;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;
use Symfony\Component\EventDispatcher;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Router;


class App {


    private $basePath;
    private $containerBuilder;
    private $routes;
    private $request;
    private $response;

    protected $matcher;
    protected $resolver;
    protected $dispatcher;
    protected $argumentResolver;

    public static $instance = null;

    public static function getInstance($path = null)
    {
        if (is_null(static::$instance)) {
            static::$instance = new static($path);
        }

        return static::$instance;
    }

    private function __construct($basePath)
    {
        $this->setRequest();
        $this->setRoutes();
        $this->setContainerBuilder();
        $this->basePath = $basePath;
    }

    /**
     * @return mixed
     */
    public function getContainerBuilder()
    {
        return $this->containerBuilder;
    }

    public function setRequest() {
        $this->request = Request::createFromGlobals();
    }

    private function setRoutes() {
        $fileLocator = new FileLocator(array(__DIR__));
        $router = new Router(
            new YamlFileLoader($fileLocator),
            BASEPATH.'/config/routes.yaml',
            array('cache_dir' => BASEPATH.'/storage/cache')
        );
        $this->routes = $router->getRouteCollection();
    }

    public function run() {

        $this->compileContainer();
        $kernel = $this->get('kernel');
        $this->response = $kernel->handle($this->request);
        $this->response->send();
        $kernel->terminate($this->request, $this->response);
    }

    private function setContainerBuilder()
    {
        $containerBuilder = new DependencyInjection\ContainerBuilder();

        $loader = new DependencyInjection\Loader\YamlFileLoader($containerBuilder, new FileLocator(__DIR__));
        $loader->load(BASEPATH.'/config/services.yaml');

        $containerBuilder->register('context', Routing\RequestContext::class);
        $containerBuilder->register('matcher', Routing\Matcher\UrlMatcher::class)
            ->setArguments([$this->routes, new Reference('context')])
        ;
        $containerBuilder->register('request_stack', HttpFoundation\RequestStack::class);
        $containerBuilder->register('controller_resolver', HttpKernel\Controller\ContainerControllerResolver::class)
            ->setArguments([$containerBuilder]);
        $containerBuilder->register('argument_resolver', HttpKernel\Controller\ArgumentResolver::class);

        $containerBuilder->register('listener.router', HttpKernel\EventListener\RouterListener::class)
            ->setArguments([new Reference('matcher'), new Reference('request_stack')])
        ;
        $containerBuilder->register('listener.response', HttpKernel\EventListener\ResponseListener::class)
            ->setArguments(['UTF-8'])
        ;
        $containerBuilder->register('listener.exception', HttpKernel\EventListener\ExceptionListener::class)
            ->setArguments(['App\Http\IndexController::indexAction'])
        ;
        $containerBuilder->register('dispatcher', EventDispatcher\EventDispatcher::class)
            ->addMethodCall('addSubscriber', [new Reference('listener.router')])
            ->addMethodCall('addSubscriber', [new Reference('listener.response')])
            ->addMethodCall('addSubscriber', [new Reference('listener.exception')])
        ;

        $containerBuilder->register('kernel',  Kernel::class)
            ->setArguments([
                new Reference('dispatcher'),
                new Reference('controller_resolver'),
                new Reference('request_stack'),
                new Reference('argument_resolver'),
            ])->setPublic('true');
        ;


        $this->containerBuilder = $containerBuilder;
    }

    public function get($key) {
        return $this->containerBuilder->get($key);
    }

    private function compileContainer()
    {
        $this->containerBuilder->compile();
    }
}
