<?php
/**
 * Created by PhpStorm.
 * User: MeitsWorkPc
 * Date: 18.02.2019
 * Time: 22:41
 */

namespace App\Http;
use Symfony\Component\HttpFoundation\Response;


class IndexController
{
    public function indexAction() {
        
        return new Response('hello');
    }
}