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
        $this->containerBuilder->compile();

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
//dd($containerBuilder);
//dd($containerBuilder->get('App\Http\IndexController'));
        $containerBuilder->register('context', Routing\RequestContext::class)->setPublic(true);
        $containerBuilder->register('matcher', Routing\Matcher\UrlMatcher::class)->setPublic(true)
            ->setArguments([$this->routes, new Reference('context')])->setPublic(true)
        ;
        $containerBuilder->register('request_stack', HttpFoundation\RequestStack::class)->setPublic(true);
        $containerBuilder->register('controller_resolver', HttpKernel\Controller\ControllerResolver::class)->setPublic(true);
        $containerBuilder->register('argument_resolver', HttpKernel\Controller\ArgumentResolver::class)->setPublic(true);

        $containerBuilder->register('listener.router', HttpKernel\EventListener\RouterListener::class)
            ->setArguments([new Reference('matcher'), new Reference('request_stack')])->setPublic(true)
        ;
        $containerBuilder->register('listener.response', HttpKernel\EventListener\ResponseListener::class)
            ->setArguments(['UTF-8'])
        ;
        $containerBuilder->register('listener.exception', HttpKernel\EventListener\ExceptionListener::class)
            ->setArguments(['App\Http\IndexController::indexAction'])->setPublic(true)
        ;
        $containerBuilder->register('dispatcher', EventDispatcher\EventDispatcher::class)
            ->addMethodCall('addSubscriber', [new Reference('listener.router')])
            ->addMethodCall('addSubscriber', [new Reference('listener.response')])
            ->addMethodCall('addSubscriber', [new Reference('listener.exception')])->setPublic(true)
        ;

        $containerBuilder->registerForAutoconfiguration(Command::class)
            ->addTag('console.command')->setPublic(true);
        $containerBuilder->registerForAutoconfiguration(ResourceCheckerInterface::class)
            ->addTag('config_cache.resource_checker')->setPublic(true);
        $containerBuilder->registerForAutoconfiguration(EnvVarProcessorInterface::class)
            ->addTag('container.env_var_processor')->setPublic(true);
        $containerBuilder->registerForAutoconfiguration(ServiceLocator::class)
            ->addTag('container.service_locator')->setPublic(true);
        $containerBuilder->registerForAutoconfiguration(ServiceSubscriberInterface::class)
            ->addTag('container.service_subscriber')->setPublic(true);
        $containerBuilder->registerForAutoconfiguration(ArgumentValueResolverInterface::class)
            ->addTag('controller.argument_value_resolver')->setPublic(true);
        $containerBuilder->registerForAutoconfiguration(AbstractController::class)
            ->addTag('controller.service_arguments')->setPublic(true);
        $containerBuilder->registerForAutoconfiguration('Symfony\Bundle\FrameworkBundle\Controller\Controller')
            ->addTag('controller.service_arguments')->setPublic(true);

        $containerBuilder->register('kernel',  Kernel::class)
            ->setArguments([
                new Reference('dispatcher'),
                new Reference('controller_resolver'),
                new Reference('request_stack'),
                new Reference('argument_resolver'),
            ])->setPublic(true)
        ;


        $this->containerBuilder = $containerBuilder;
    }

    public function get($key) {
        return $this->containerBuilder->get($key);
    }
}
