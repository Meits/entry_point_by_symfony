<?php
/**
 * Created by PhpStorm.
 * User: MeitsWorkPc
 * Date: 19.02.2019
 * Time: 21:58
 */

namespace App\Http;


use App\System\App;
use \App\System\View\IView;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\TaggedContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\System\Controller\IController;

class Controller implements IController
{
    protected $view;
    protected $container;

    public function __construct(IView $view) {
        //dump($view);
        if(!$view) {
            //$this->container = app();
            //$view = $this->container->get('App\System\View\IView');
        }

        $this->view = $view;
        //$this->view = $view;
    }

    public function render($path, $data = []) {
        return new Response($this->view->make($path, $data));
    }
}