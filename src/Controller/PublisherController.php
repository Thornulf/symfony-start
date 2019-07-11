<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherFormType;
use App\Repository\PublisherRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PublisherController
 * @Route("/publisher")
 * @package App\Controller
 */
class PublisherController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * PublisherController constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/list", name="publisher")
     */
    public function index()
    {
        $repo = $this->em->getRepository(Publisher::class);
        $publisherList = $repo->findAll();

        return $this->render('publisher/index.html.twig', [
            'controller_name' => 'PublisherController',
            'publisherList' => $publisherList
        ]);
    }

    /**
     * @Route("/new", name="publisher_new")
     * @Route("/edit/{id}", name="publisher_edit")
     * @param Request $request
     * @param PublisherRepository $repository
     * @param null $id
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function formPublisherAction(Request $request, PublisherRepository $repository, $id=null) {
        if($id) {
            $buttonLabel = "Modifier";
            $publisher = $repository->findOneById($id);
        } else {
            $buttonLabel = "Ajouter";
            $publisher = new Publisher();
        }

        $form = $this->createForm(PublisherFormType::class, $publisher);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $publisher = $form->getData();
            $this->addFlash("success", "Votre éditeur a été ajouté");
            $this->em->persist($publisher);
            $this->em->flush();

            return $this->redirectToRoute("publisher");
        }

        return $this->render("/publisher/form.html.twig", ["publisherForm" => $form->createView(), "buttonLabel" => $buttonLabel]);
    }

    /**
     * @Route("/delete/{id}", name="publisher_delete")
     * @param EntityManagerInterface $em
     * @param Publisher $publisher
     * @return RedirectResponse
     */
    public function deleteAuthor(EntityManagerInterface $em, Publisher $publisher) {
        $em->remove($publisher);
        $em->flush();

        $this->addFlash("success", "Suppression OK");

        return $this->redirectToRoute("publisher");
    }
}
