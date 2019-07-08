<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{

    /**
     * @Route ("/home/{name}", name="home", defaults={"name"="toto"})
     * @param Request $request
     * @param $name
     * @return Response
     */
    public function indexAction(Request $request, $name) {
        $age = $request->get("age") ?? 10;

        $fruits = [
            "pommes", "poires", "oranges", "grenades"
        ];

        return $this->render("home/home.html.twig", ["name"=>$name, "age"=>$age, "fruitList"=>$fruits]);
    }

    /**
     * @Route("/add/{number1}/{number2}", name="home_add", requirements={"number1"="\d+", "number2"="\d+"})
     * @param $number1
     * @param $number2
     * @return Response
     */
    public function addAction($number1, $number2) {
        $result = $number1 + $number2;
        return $this->render("home/add.html.twig", ["n1"=>$number1,"n2"=>$number2, "result"=>$result]);
    }
}