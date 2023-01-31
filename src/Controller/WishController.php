<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/wish', name: 'wish')]
class WishController extends AbstractController
{
    #[Route('/', name: '_list')]
    public function list(): Response
    {
        $date = new \DateTime();
        return $this->render('wish/list.html.twig', [
            'today' => $date
        ]);
    }

    #[Route('/detail/{id}', name: '_detail')]
    public function detail(int $id): Response
    {
        $date = new \DateTime();
        return $this->render('wish/detail.html.twig', [
            'today' => $date
        ]);
    }
}
