<?php

declare(strict_types=1);

namespace App\System\Routing;

use Symfony\Component\Routing\Loader\AttributeClassLoader;
use Symfony\Component\Routing\Route;

/**
 * Turns #[Route] attributes on controller methods into Route objects,
 * pointing each one at "FQCN::method" so the container-aware controller
 * resolver can fetch the controller as a service.
 */
final class AttributeRouteLoader extends AttributeClassLoader
{
    protected function configureRoute(Route $route, \ReflectionClass $class, \ReflectionMethod $method, object $attribute): void
    {
        $route->setDefault('_controller', $class->getName() . '::' . $method->getName());
    }
}
