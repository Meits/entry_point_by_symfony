<?php

define('BASEPATH', dirname(__DIR__));

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;
use Symfony\Component\EventDispatcher;



$routes = include("routing.php");

$containerBuilder = new DependencyInjection\ContainerBuilder();
$containerBuilder->register('context', Routing\RequestContext::class);
$containerBuilder->register('matcher', Routing\Matcher\UrlMatcher::class)
    ->setArguments([$routes, new Reference('context')])
;
$containerBuilder->register('request_stack', HttpFoundation\RequestStack::class);
$containerBuilder->register('controller_resolver', HttpKernel\Controller\ControllerResolver::class);
$containerBuilder->register('argument_resolver', HttpKernel\Controller\ArgumentResolver::class);

$containerBuilder->register('framework',  App\System\App::class)
    ->setArguments([
        new Reference('controller_resolver'),
        new Reference('matcher'),
        new Reference('argument_resolver'),
    ])
;

return $containerBuilder->get('framework');


/*
 $app = App\System\App::getInstance(BASEPATH);
$config = new \App\System\Config\Config('config');
$config->addConfig('routes.yaml');
$config->addConfig('app.yaml');
$config->addConfig('database.yaml');

$app->add("config",$config);

if(config('System.orm') == true) {
    $orm = new App\System\Database\Orm(config('database'));
    $app->add("orm",$orm);
}

return $app;
*/