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

class PageController  extends Controller
{
    public function show(Request $request, $alias = 'ku') {
        return $this->render("page",['alias' => $alias]);
    }
}