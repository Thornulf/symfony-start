<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Author;
use App\Entity\Comment;
use App\Form\ArticleFormType;
use App\Form\CommentFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     * @Route("/list/page/{pageNumber}", name="article", requirements={"pageNumber" = "\d+"}, defaults={"pageNumber" = "1"})
     * @param $pageNumber
     * @return Response
     */
    public function index($pageNumber)
    {
        $nbArticlePerPage = 10;
        $repoArticle = $this->em->getRepository(Article::class);
        $repoAuthor = $this->em->getRepository(Author::class);

        $lastArticleList = $repoArticle->getLastArticles(10);
        $authorList = $repoAuthor->getAuthorList();

        $numberOfArticles = $repoArticle->getTotalNumberOfArticle();
        $nbPage = ceil($numberOfArticles / $nbArticlePerPage);

        $pageNumber = Min($pageNumber, $nbPage);

        $articleList = $repoArticle->getAllArticleByPage($nbArticlePerPage, $pageNumber);

        $startPage = $pageNumber-5 <0 ? $pageNumber : $pageNumber - 5;
        $endPage = Min($startPage + 10, $nbPage);

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articleList' => $articleList,
            'numberOfArticles' => $numberOfArticles,
            'nbPages' => $nbPage,
            'startPage' => $startPage,
            'endPage' => $endPage,
            'pageNumber' => $pageNumber,
            'lastArticleList' => $lastArticleList,
            'authorList' => $authorList
        ]);
    }

    /**
     * @Route("/new", name ="article_new")
     * @Route("/edit/{id}", name="article_edit")
     * @param Request $request
     * @param Article|null $article
     * @return Response
     */
    public function addEditArticleAction(Request $request,UploadableManager $uploadableManager, Article $article = null)
    {
        if(!$article) {
            $article = new Article();
        }

        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $article = $form->getData();

            if($article->getUploadedFile() instanceof UploadedFile) {
                $uploadableManager->markEntityToUpload($article, $article->getUploadedFile());
            }

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
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function showArticleAction(Article $article) {
        //Si on passe $id en paramètre mais symfony fais le lien automatiquement avec id par rapport à la clé primaire sur l'entité
        //$repo = $this->em->getRepository(Article::class);
        //$article = $repo->findOneBy(["id"=>$id]);

        if(!$article) {
            throw $this->createNotFoundException("Pas d'article avec cet id");
        }

        return $this->render("article/show.html.twig", [
            "article"=>$article
        ]);
    }

    /**
     * @Route("/show/{slug}", name="article_show_by_slug")
     * @ParamConverter("article", options={"mapping": {"slug": "slug"} })
     * @param Article $article
     * @return Response
     * @throws \Exception
     */
    public function showArticleBySlugAction(Article $article) {
        if(!$article) {
            throw $this->createNotFoundException("Pas d'article avec cet id");
        }

        $comment = new Comment();
        $comment->setArticle($article);
        $comment->setCreatedAt(new \DateTime());

        $form = $this->createForm(CommentFormType::class, $comment);

        return $this->render("article/show.html.twig", [
            "article"=>$article,
            "commentForm" => $form->createView()
        ]);
    }
}
