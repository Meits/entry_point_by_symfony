<?php

define('BASEPATH', dirname(__DIR__));

$app = App\System\App::getInstance(BASEPATH);

$config = new \App\System\Config\Config('config');
$config->addConfig('routes.yaml');


dump($config->getConfig());

return $app;