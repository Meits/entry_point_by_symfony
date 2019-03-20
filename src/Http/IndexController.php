<?php
/**
 * Created by PhpStorm.
 * User: MeitsWorkPc
 * Date: 18.02.2019
 * Time: 22:41
 */

namespace App\Http;


use App\Entities\Book;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

class IndexController extends Controller
{


    public function __construct()
    {
        parent::__construct();
    }

    public function indexAction(Request $request) {

        //$em = app()->get('orm')->getEntityManager();
        //dd($em);
        return $this->render("index.html.twig",['title' => "kuuuu"]);
    }
}