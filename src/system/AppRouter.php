<?php
/**
 * Created by PhpStorm.
 * User: MeitsWorkPc
 * Date: 18.02.2019
 * Time: 22:23
 */

namespace App\System;


use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Controller;
use Symfony\Component\Routing\RouteCollection;

use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;

class AppRouter
{

    private $router;
    private $requestContext;

    /**
     * AppRouter constructor.
     */
    public function __construct()
    {
        $this->requestContext = new RequestContext();
        $this->requestContext->fromRequest(Request::createFromGlobals());
    }

    /**
     * @return array
     */
    public function match() {
        
        return $this->getRoutes()->match($this->requestContext->getPathInfo());
    }

    /**
     * @return array
     */
    public function test() {
        
        $request = Request::createFromGlobals();
        $context = new RequestContext();
        $context->fromRequest($request);

        $routes = $this->getRoutes()->getRouteCollection();
        $matcher = new Routing\Matcher\UrlMatcher($routes, $context);

        $controllerResolver = new HttpKernel\Controller\ControllerResolver();
        $argumentResolver = new HttpKernel\Controller\ArgumentResolver();

        try {
            $request->attributes->add($matcher->match($request->getPathInfo()));
            $controller = $controllerResolver->getController($request);
            $arguments = $argumentResolver->getArguments($request, $controller);

            $response = call_user_func_array($controller, $arguments);
            
        } catch (Routing\Exception\ResourceNotFoundException $exception) {
            $response = new Response('Not Found', 404);
        } catch (Exception $exception) {
            $response = new Response('An error occurred', 500);
        }

    }

    /**
     * @return Router
     */
    private function getRoutes() {
        $fileLocator = new FileLocator(array(__DIR__));
        $this->router = new Router(
            new YamlFileLoader($fileLocator),
            '../../config/routes.yaml',
            array('cache_dir' => '../storage/cache')
        );
        return $this->router;
    }
}