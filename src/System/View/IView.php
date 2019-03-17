<?php

namespace App\System\View;

interface IView 
{
    public function make($path, $data = []);
}