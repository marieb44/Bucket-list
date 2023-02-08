<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\UserRepository;
use App\Repository\WishRepository;
use App\Services\Censurator;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
        //$wishes = $wishRepository->findBy([], ["dateCreated" => "DESC"], null, 0);

        //optimisation requête avec jointure suite à ajout relation avec table Category
        $wishes = $wishRepository->findAllWithCategory();
        return $this->render('wish/list.html.twig', [
            'today' => $date,
            'wishes' => $wishes
        ]);
    }

    #[Route('/detail/{id}', name: '_detail')]
    public function detail(
        int            $id,
        WishRepository $wishRepository,
        Censurator     $censurator   //injection de service
    ): Response
    {
        $date = new \DateTime();
        //$wish = $wishRepository->findOneBy(["id" => $id]);
        //optimisation requête
        $wish = $wishRepository->findOneWithCategory($id);
        //dump($wish);

        //appel au service de "purification" pour supprimer les gros mots
        $wish->setTitle($censurator->purify($wish->getTitle()));
        $wish->setDescription($censurator->purify($wish->getDescription()));

        return $this->render('wish/detail.html.twig', [
            'today' => $date,
            'wish' => $wish
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/create', name: '_create')]
    public function add(
        EntityManagerInterface $em,
        Request                $request,
        UserRepository         $userRepository,
        Censurator             $censurator      //injection de service
    ): Response
    {
        $date = new \DateTime();

        //1.créer une instance de Wish
        $wish = new Wish();
        $wish->setAuthor($this->getUser()->getUserIdentifier());
//        si besoin d'afficher un autre attribut que celui indiqué à la création
//        de l'entité User comme identifiant unique
//        $utilisateur = $userRepository->findOneBy(["username" => $this->getUser()->getUserIdentifier()]);
//        $wish->setAuthor($utilisateur->getUsername());

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

                //appel au service de "purification" pour supprimer les gros mots
                $wish->setTitle($censurator->purify($wish->getTitle()));
                $wish->setDescription($censurator->purify($wish->getDescription()));

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
