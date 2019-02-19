<?php
/**
 * Created by PhpStorm.
 * User: MeitsWorkPc
 * Date: 18.02.2019
 * Time: 23:17
 */

namespace App\Http;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController
{
    public function show(Request $request, $alias = 'ku') {
        return new Response($alias);
    }
}