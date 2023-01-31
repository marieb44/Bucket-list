<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_home')]
    public function home(): Response
    {
        $date = new \DateTime();
        return $this->render('main/home.html.twig',
        [
            'today' => $date
        ]);
    }

    #[Route('/about-us', name: 'main_about-us')]
    public function aboutUs(): Response
    {
        //l'application est appelée depuis "public"
        $json = file_get_contents("../data/team.json");

        //transformation du fichier en tableau associatif
        $auteurs = json_decode($json, true);

        $date = new \DateTime();
        return $this->render('main/about-us.html.twig', [
            'today' => $date,
            'auteurs' => $auteurs
        ]);
    }

}
