<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Entities\Book;
use App\System\App;
use Doctrine\ORM\Tools\SchemaTool;

/** @var App $app */
$app = require __DIR__ . '/../bootstrap/app.php';
$entityManager = $app->getOrm()->getEntityManager();

$schemaTool = new SchemaTool($entityManager);
$metadata = $entityManager->getMetadataFactory()->getAllMetadata();
$schemaTool->updateSchema($metadata);

$existing = $entityManager->getRepository(Book::class)->findAll();
if ($existing === []) {
    foreach (['The Pragmatic Programmer', 'Clean Code', 'Domain-Driven Design'] as $title) {
        $entityManager->persist(new Book($title));
    }
    $entityManager->flush();
}

echo "Database ready at var/database.sqlite\n";
