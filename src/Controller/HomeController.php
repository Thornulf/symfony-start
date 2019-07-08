<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/test")
 * Class HomeController
 * @package App\Controller
 */
class HomeController
{

    /**
     * @Route ("/home/{name}", name="home", defaults={"name"="toto"})
     * @param Request $request
     * @param $name
     * @return Response
     */
    public function indexAction(Request $request, $name) {
        $age = $request->get("age") ?? 10;
        return new Response("hello Symfony $name vous avez $age ans");
    }

    /**
     * @Route("/add/{number1}/{number2}", name="home_add", requirements={"number1"="\d+", "number2"="\d+"})
     * @param $number1
     * @param $number2
     * @return Response
     */
    public function addAction($number1, $number2) {
        $result = $number1 + $number2;
        return new Response("la somme de $number1 et $number2 fait $result");
    }
}