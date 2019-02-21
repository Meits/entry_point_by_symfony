<?php

define('BASEPATH', dirname(__DIR__));

$app = App\System\App::getInstance(BASEPATH);

$config = new \App\System\Config\Config('config');
$config->addConfig('routes.yaml');

$app->add("config",$config);

return $app;