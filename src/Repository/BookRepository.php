<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entities\Book;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<Book>
 */
final class BookRepository extends EntityRepository
{
}
