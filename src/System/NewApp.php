<?php
/**
 * Created by PhpStorm.
 * User: MeitsWorkPc
 * Date: 25.03.2019
 * Time: 21:19
 */

namespace App\System;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class NewApp extends ContainerBuilder
{
    public static function buildContainer($rootPath)
    {

        $container = new self();
        $container->setParameter('app_root', $rootPath);
        $loader = new YamlFileLoader(
            $container,
            new FileLocator($rootPath . '/config')
        );
        $loader->load('services2yaml');
        $container->compile();

        return $container;
    }

    public function get(
        $id,
        $invalidBehavior = ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE
    ) {
        if (strtolower($id) == 'service_container') {
            if (ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE
                !==
                $invalidBehavior
            ) {
                return;
            }
            throw new InvalidArgumentException(
                'The service definition "service_container" does not exist.'
            );
        }

        return parent::get($id, $invalidBehavior);
    }
}