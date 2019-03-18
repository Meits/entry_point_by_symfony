<?php

namespace App\System;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Controller;
use Symfony\Component\Routing\RouteCollection;

use Symfony\Component\Routing;


class App extends HttpKernel {

   /* private $request;
    public $router;
    public $routes;
    private $requestContext;
    
    private $controller;
    private $arguments;

    private $basePath;*/

    //public static $instance = null;

    private $container = [];
    
    /*public static function getInstance($path = null)
    {
        if (is_null(static::$instance)) {
            static::$instance = new static($path);
        }

        return static::$instance;
    }*/
    
    /*public function __construct($basePath)
    {
        $this->request = $this->setRequest();

        $this->requestContext = $this->setRequestContext();

        $this->router = $this->setRouter();

        $this->routes = $this->router->getRouteCollection();

        $this->basePath = $basePath;

    }*/

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
        $this->handle(Request::createFromGlobals())->send();
        return;
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

}
