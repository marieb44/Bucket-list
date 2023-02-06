<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/wish', name: 'wish')]
class WishController extends AbstractController
{
    #[Route('/', name: '_list')]
    public function list(
        WishRepository $wishRepository
    ): Response
    {
        $date = new \DateTime();
        //$wishes = $wishRepository->findAll();
        $wishes = $wishRepository->findBy([], ["dateCreated" => "DESC"], null, 0);
        return $this->render('wish/list.html.twig', [
            'today' => $date,
            'wishes' => $wishes
        ]);
    }

    #[Route('/detail/{id}', name: '_detail')]
    public function detail(
        int            $id,
        WishRepository $wishRepository
    ): Response
    {
        $date = new \DateTime();
        $wish = $wishRepository->findOneBy(["id" => $id]);
        return $this->render('wish/detail.html.twig', [
            'today' => $date,
            'wish' => $wish
        ]);
    }

    #[Route('/create', name: '_create')]
    public function add(
        EntityManagerInterface $em,
        Request                $request
    ): Response
    {
        $date = new \DateTime();

        //1.créer une instance de Wish
        $wish = new Wish();

//        $wish = (new Wish())
//            ->setIsPublished(true)
//            ->setDateCreated($date);

        //2.créer une instance du formulaire
        $wishForm = $this->createForm(WishType::class, $wish);

        //4.le controller affiche(1,2,3) ET traite
        $wishForm->handleRequest($request);
        if ($wishForm->isSubmitted()) {
            try {
                //initialisation des champs non saisis
                $wish->setIsPublished(true);
                $wish->setDateCreated($date);
                if ($wishForm->isValid()) {
                    $em->persist($wish);
                }
            } catch (Exception $exception) {
                dd($exception->getMessage()); //dd = dump and die
            }
            $em->flush();

            //Ajout message flash suite à l'insertion en base
            $this->addFlash('success', 'Idea successfully added !');

            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }

        //3.envoyer le formulaire au twig
        return $this->render('wish/create.html.twig', [
            'today' => $date,
            'wishForm' => $wishForm
        ]);

    }
}
