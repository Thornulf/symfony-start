<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article")
 * Class ArticleController
 * @package App\Controller
 */
class ArticleController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ArticleController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/list", name="article")
     */
    public function index()
    {
        $repo = $this->em->getRepository(Article::class);
        $articleList = $repo->findAll();

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articleList' => $articleList
        ]);
    }

    /**
     * @Route("/new", name ="article_new")
     * @Route("/edit/{id}", name="article_edit")
     * @param Request $request
     * @param Article|null $article
     * @return Response
     */
    public function addEditArticleAction(Request $request, Article $article = null)
    {
        if(!$article) {
            $article = new Article();
        }

        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $article = $form->getData();
            $this->em->persist($article);
            $this->em->flush();
            return $this->redirectToRoute("article");
        }

        return $this->render("/article/form.html.twig", [
            "articleForm" => $form->createView()
        ]);
    }

    /**
     * @Route("/show/{id}", name="article_show", requirements={"id"="\d+"})
     * @param Article $article
     * @return Response
     */
    public function showArticleAction(Article $article) {
        //Si on passe $id en paramètre mais symfony fais le lien automatiquement avec id par rapport à la clé primaire sur l'entité
        //$repo = $this->em->getRepository(Article::class);
        //$article = $repo->findOneBy(["id"=>$id]);

        if(!$article) {
            throw $this->createNotFoundException("Pas d'article avec cet id");
        }

        return $this->render("article/show.html.twig", ["article"=>$article]);
    }
}
