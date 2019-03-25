<?php
/**
 * Created by PhpStorm.
 * User: Meits
 * Date: 21-Mar-19
 * Time: 10:18
 */

namespace App\System;

use App\Http\Controller;
use App\Http\IndexController;
use App\System\View\IView;
use App\System\View\View;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;
use Symfony\Component\Templating\TemplateNameParser;
use Twig\Environment;


class SimpleKernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles()
    {
        $bundles = [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
        ];


        if ($this->getEnvironment() == 'dev') {
            $bundles[] = new WebProfilerBundle();
        }

        return $bundles;
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {

        $loader->load(BASEPATH.'/config/framework.yaml');
        $loader->load(BASEPATH.'/config/services.yaml');


        // configure WebProfilerBundle only if the bundle is enabled
        if (isset($this->bundles['WebProfilerBundle'])) {
            $c->loadFromExtension('web_profiler', [
                'toolbar' => true,
                'intercept_redirects' => false,
            ]);
        }

        //$c->register('filesystemLoader',\Twig\Loader\FilesystemLoader::class)->setArguments([[BASEPATH.'/resources/views/']]);
        //$c->register('environment',Environment::class)->setArguments([new Reference('filesystemLoader')]);
        //$c->register('templateNameParser',TemplateNameParser::class);
        /*$c->register('TwigEngine',\Symfony\Bridge\Twig\TwigEngine::class)
            ->setArguments([new Reference('environment'),new Reference('templateNameParser')]);*/
        /*$c->register('view', \App\System\View\View::class)
            ->setArguments([new Reference('TwigEngine')])
        ;*/
        //$c->setAlias(IView::class, 'view');

        ///dd($c);



    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        // import the WebProfilerRoutes, only if the bundle is enabled
        if (isset($this->bundles['WebProfilerBundle'])) {
            $routes->import('@WebProfilerBundle/Resources/config/routing/wdt.xml', '/_wdt');
            $routes->import('@WebProfilerBundle/Resources/config/routing/profiler.xml', '/_profiler');
        }

        // load the annotation routes
        //$routes->import(BASEPATH.'/src/Http/', '/', 'annotation');
        $routes->import(BASEPATH.'/config/routes.yaml');

    }

    // optional, to use the standard Symfony cache directory
    public function getCacheDir()
    {
        return __DIR__.'/../var/cache/'.$this->getEnvironment();
    }

    // optional, to use the standard Symfony logs directory
    public function getLogDir()
    {
        return __DIR__.'/../var/log';
    }
}