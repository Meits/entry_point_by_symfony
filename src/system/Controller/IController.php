<?php

namespace App\System\Controller;

interface IController 
{
    public function render($path, $data = []);
}