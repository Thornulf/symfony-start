<?php

namespace App\Controller;

use App\Entity\Author;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/author")
 * Class AuthorController
 * @package App\Controller
 */
class AuthorController extends AbstractController
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * AuthorController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/list", name="author")
     */
    public function index()
    {
        $repo = $this->em->getRepository(Author::class);
        $authorList = $repo->findAll();

        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
            'authorList' => $authorList
        ]);
    }

    /**
     * @Route("/new")
     */
    public function newAuthorAction(){
        $author = new Author();
        $author->setName("Tolkien")
                ->setFirstName("John Ronald Reuel")
                ->setGender("M")
                ->setBirthDate(new \DateTime("now -126 years"));

        $entityManager = $this->em;
        $entityManager->persist($author);
        $entityManager->flush();

        return $this->redirectToRoute("author");
    }

    /**
     * @Route("/show/{id}", name="author_show", requirements={"id"="\d+"})
     * @param Author $author
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAuthorAction(Author $author) {
        if(!$author) {
            throw $this->createNotFoundException("Pas d'auteur avec cet id");
        }

        return $this->render("author/show.html.twig", ["author"=>$author]);
    }
}
