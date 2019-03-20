<?php
/**
 * Created by PhpStorm.
 * User: MeitsWorkPc
 * Date: 19.02.2019
 * Time: 22:23
 */

namespace App\System\View;

use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Templating\EngineInterface;
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
    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function make($path, $data = []) {
        return $this->templating->render($path, $data);
    }

}