<?php
/**
 * Created by PhpStorm.
 * User: MeitsWorkPc
 * Date: 19.02.2019
 * Time: 21:58
 */

namespace App\Http;


use App\System\View;
use Symfony\Component\HttpFoundation\Response;

class Controller
{
    protected $view;

    public function __construct() {
        $this->view = new View();
    }

    protected function render($path, $data = []) {
        return new Response($this->view->make($path, $data));
    }
}