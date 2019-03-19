<?php

define('BASEPATH', dirname(__DIR__));

$app = App\System\App::getInstance(BASEPATH);

$app->getContainerBuilder()->register('config', \App\System\Config\Config::class)
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
}

return $app;
