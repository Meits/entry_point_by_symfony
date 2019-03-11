<?php

define('BASEPATH', dirname(__DIR__));

$app = App\System\App::getInstance(BASEPATH);

$config = new \App\System\Config\Config('config');
$config->addConfig('routes.yaml');
$config->addConfig('app.yaml');
$config->addConfig('database.yaml');

$app->add("config",$config);

if(config('system.orm') == true) {
    $orm = new App\System\Database\Orm(config('database'));
    $app->add("orm",$orm);
}

return $app;