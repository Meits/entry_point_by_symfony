<?php
/**
 * Created by PhpStorm.
 * User: MeitsWorkPc
 * Date: 19.02.2019
 * Time: 22:23
 */

namespace App\System;

use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;


class View
{

    private $templating;

    /**
     * View constructor.
     */
    public function __construct()
    {
        $filesystemLoader = new FilesystemLoader('../resources/views/%name%.php');
        $this->templating = new PhpEngine(new TemplateNameParser(), $filesystemLoader);
    }

    public function make($path, $data = []) {
        return $this->templating->render($path, $data);
    }

}