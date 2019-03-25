<?php

use Symfony\Component\Templating\TemplateNameParser;
use Twig\Environment;

define('BASEPATH', dirname(__DIR__));

$app = App\System\App::getInstance(BASEPATH);

/*$container = \App\System\NewApp::buildContainer(BASEPATH);

$response = $container->get('response');
$response->send();

dd($container);*/






/*$app->getContainerBuilder()->register('config', \App\System\Config\Config::class)
    ->setArguments(['config'])
;
$app->get('config')->addConfigs([
    'routes.yaml',
    'app.yaml',
    'database.yaml'
]);

if(config('app.orm') == true) {
    $app->getContainerBuilder()->register('orm', \App\System\Database\Orm::class)
        ->setArguments([config('database')])
    ;
}*/

/*$app->getContainerBuilder()->register('view', \App\System\View\View::class)
    ->setArguments([ new \Symfony\Bridge\Twig\TwigEngine(new Environment(new \Twig\Loader\FilesystemLoader([BASEPATH.'/resources/views/'])),new TemplateNameParser())])
;*/


return $app;
