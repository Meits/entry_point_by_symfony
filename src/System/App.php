<?php

namespace App\System;

use Symfony\Component\Config\FileLocator;
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

    protected $matcher;
    protected $resolver;
    protected $dispatcher;
    protected $argumentResolver;

   /* public function __construct(ControllerResolverInterface $resolver, UrlMatcherInterface $matcher,ArgumentResolverInterface $argumentResolver)
    {
        //$this->dispatcher = $dispatcher;
        $this->matcher = $matcher;
        $this->resolver = $resolver;
        $this->argumentResolver = $argumentResolver;

        $this->request = $this->setRequest();
    }*/

   /* public function getBasePath() {
        return $this->basePath;
    }

    public function setRequest() {
        return Request::createFromGlobals();
    }

    public function getRequest() {
        return $this->request;
    }

    public function setRequestContext() {
        $requestContext = new RequestContext();
        $requestContext->fromRequest($this->request);

        return $requestContext;
    }

    public function getRequestContextt() {
        return $this->requestContext;
    }

    /**
     * @return Router
     */
   /* private function setRouter() {
        $fileLocator = new FileLocator(array(__DIR__));
        $router = new Router(
            new YamlFileLoader($fileLocator),
            BASEPATH.'/config/routes.yaml',
            array('cache_dir' => BASEPATH.'/storage/cache')
        );
        return $router;
    }

    public function getController() {
        $controllerResolver = new HttpKernel\Controller\ControllerResolver();
        return $controllerResolver->getController($this->request);
    }

    public function getArguments($controller) {
        $argumentResolver = new HttpKernel\Controller\ArgumentResolver();
        return $argumentResolver->getArguments($this->request, $controller);
    }**/


    public function run() {
        $kernel = $this->containerBuilder->get('kernel');
        $kernel->handle($this->request)->send();
    }

    private function setContainerBuilder()
    {
        $containerBuilder = new DependencyInjection\ContainerBuilder();
        $containerBuilder->register('context', Routing\RequestContext::class);
        $containerBuilder->register('matcher', Routing\Matcher\UrlMatcher::class)
            ->setArguments([$this->routes, new Reference('context')])
        ;
        $containerBuilder->register('request_stack', HttpFoundation\RequestStack::class);
        $containerBuilder->register('controller_resolver', HttpKernel\Controller\ControllerResolver::class);
        $containerBuilder->register('argument_resolver', HttpKernel\Controller\ArgumentResolver::class);

        $containerBuilder->register('listener.router', HttpKernel\EventListener\RouterListener::class)
            ->setArguments([new Reference('matcher'), new Reference('request_stack')])
        ;
        $containerBuilder->register('listener.response', HttpKernel\EventListener\ResponseListener::class)
            ->setArguments(['UTF-8'])
        ;
        $containerBuilder->register('listener.exception', HttpKernel\EventListener\ExceptionListener::class)
            ->setArguments(['Calendar\Controller\ErrorController::exception'])
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
            ])
        ;

        $this->containerBuilder = $containerBuilder;
    }

    /*public function add($key, $object) {
        $this->container[$key] = $object;
        return $object;
    }
    public function get($key) {
        if(isset($this->container[$key])) {
            return $this->container[$key];
        }
        return null;
    }*/

    public function get($key) {
        return $this->containerBuilder->get($key);
    }

}
