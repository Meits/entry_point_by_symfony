<?php
/**
 * Created by PhpStorm.
 * User: MeitsWorkPc
 * Date: 19.02.2019
 * Time: 22:23
 */

namespace App\System\View;

use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Twig\Environment;


class View implements IView
{

    private $templating;

    /**
     * View constructor.
     */
    public function __construct()
    {
        //$filesystemLoader = new FilesystemLoader(BASEPATH.'/resources/views/%name%.php');
        //$this->templating = new PhpEngine(new TemplateNameParser(), $filesystemLoader);
        $this->templating = new TwigEngine(new Environment(new \Twig\Loader\FilesystemLoader([BASEPATH.'/resources/views/'])),new TemplateNameParser());
    }

    public function make($path, $data = []) {
        return $this->templating->render($path, $data);
    }

}