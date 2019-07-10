<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorFormType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/new", name="author_new")
     * @Route("/edit/{id}", name="author_edit")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param AuthorRepository $repository
     * @param null $id
     * @return Response
     */
    public function newAuthorAction(EntityManagerInterface $em, Request $request, AuthorRepository $repository, $id=null)
    {

        if($id) {
            $buttonLabel = "Modifier";
            $author = $repository->findOneById($id);
        } else {
            $buttonLabel = "Ajouter";
            $author = new Author();
        }

        $form = $this->createForm(AuthorFormType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $author = $form->getData();

             //Si dans les options la classe n'est pas liée à une entité
//            $data = $form->getData();
//            $author = new Author();
//            $author->setName($data["name"])
//                ->setFirstName($data["firstName"])
//                ->setGender($data["gender"])
//                ->setBirthDate(new DateTime($data["birthDate"]));

            $this->addFlash("success", "Votre auteur a été ajouté");
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute("author");
        }

        return $this->render("/author/form.html.twig", ["authorForm" => $form->createView(), "buttonLabel" => $buttonLabel]);
    }

    /**
     * @Route("/delete/{id}", name="author_delete")
     * @param EntityManagerInterface $em
     * @param Author $author
     * @return RedirectResponse
     */
    public function deleteAuthor(EntityManagerInterface $em, Author $author) {
        $em->remove($author);
        $em->flush();

        $this->addFlash("success", "Suppression OK");

        return $this->redirectToRoute("author");
    }

}
