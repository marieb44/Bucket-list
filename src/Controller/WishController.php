<?php

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
