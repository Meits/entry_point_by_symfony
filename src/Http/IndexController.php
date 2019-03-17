<?php
/**
 * Created by PhpStorm.
 * User: MeitsWorkPc
 * Date: 18.02.2019
 * Time: 22:41
 */

namespace App\Http;


use App\Entities\Book;

class IndexController extends Controller
{


    public function __construct()
    {
        parent::__construct();
    }

    public function indexAction() {

        //$em = app()->get('orm')->getEntityManager();
        return $this->render("index",['title' => "kuuuu"]);
    }
}