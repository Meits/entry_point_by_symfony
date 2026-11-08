<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\ErrorHandler\Debug;

if (\getenv('APP_DEBUG') !== '0') {
    Debug::enable();
}

/** @var \App\System\App $app */
$app = require __DIR__ . '/../bootstrap/app.php';
$app->run();
