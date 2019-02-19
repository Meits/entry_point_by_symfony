<?php
/**
 * Created by PhpStorm.
 * User: MeitsWorkPc
 * Date: 18.02.2019
 * Time: 22:41
 */

namespace App\Http;


class IndexController extends Controller
{
    public function indexAction() {
        
        return $this->render("index",['title' => "kuuuu"]);
    }
}