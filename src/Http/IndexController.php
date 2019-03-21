<?php
/**
 * Created by PhpStorm.
 * User: MeitsWorkPc
 * Date: 18.02.2019
 * Time: 22:41
 */

namespace App\Http;


use App\Entities\Book;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{

    /**
     * Matches /blog exactly
     *
     * @Route("/", name="home")
     */
    public function indexAction(Request $request) {

        //$em = app()->get('orm')->getEntityManager();
        return $this->render("index.html.twig",['title' => "kuuuu"]);
    }
}