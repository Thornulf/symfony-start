<?php

namespace App\Controller;

use App\Entity\BookGenre;
use App\Form\BookGenreFormType;
use App\Repository\BookGenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book")
 * Class BookGenreController
 * @package App\Controller
 */
class BookGenreController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * BookGenreController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/genre/list", name="book_genre")
     */
    public function index()
    {
        $repo = $this->em->getRepository(BookGenre::class);
        $bookGenreList = $repo->findAll();

        return $this->render('book_genre/index.html.twig', [
            'controller_name' => 'BookGenreController',
            'bookGenreList' => $bookGenreList
        ]);
    }

    /**
     * @Route("/new", name="genre_new")
     * @Route("/edit/{id}", name="genre_edit")
     * @param Request $request
     * @param BookGenreRepository $repository
     * @param null $id
     * @return Response
     */
    public function formBookGenreAction(Request $request, BookGenreRepository $repository, $id=null) {
        if($id) {
            $buttonLabel = "Modifier";
            $bookGenre = $repository->findOneById($id);
        } else {
            $buttonLabel = "Ajouter";
            $bookGenre = new BookGenre();
        }

        $form = $this->createForm(BookGenreFormType::class, $bookGenre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $bookGenre = $form->getData();
            $this->addFlash("success", "Le nouveau genre a été ajouté");
            $this->em->persist($bookGenre);
            $this->em->flush();

            return $this->redirectToRoute("book_genre");
        }

        return $this->render("/book_genre/form.html.twig", ["bookGenreForm" => $form->createView(), "buttonLabel" => $buttonLabel]);
    }

    /**
     * @Route("/delete/{id}", name="genre_delete")
     * @param EntityManagerInterface $em
     * @param BookGenre $bookGenre
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteBookGenre(EntityManagerInterface $em, BookGenre $bookGenre) {
        $em->remove($bookGenre);
        $em->flush();

        $this->addFlash("success", "Suppression OK");

        return $this->redirectToRoute("book_genre");
    }
}
