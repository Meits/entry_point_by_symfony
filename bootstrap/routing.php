<?php
/**
 * Created by PhpStorm.
 * User: MeitsWorkPc
 * Date: 17.03.2019
 * Time: 22:15
 */

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Router;

$fileLocator = new FileLocator(array(__DIR__));
$router = new Router(
    new YamlFileLoader($fileLocator),
    BASEPATH.'/config/routes.yaml',
    array('cache_dir' => BASEPATH.'/storage/cache')
);

return $router->getRouteCollection();