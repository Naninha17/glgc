<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * Contrôleur de la page (principale et unique)
     */

    #[Route('/', name: 'main_home')]
    public function home(): Response
    {


        return $this->render('main/home.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * Contrôleur de la page des mentions légales
     */

    #[Route('/mentions-legales/', name: 'legal_notice')]
    public function legalNotice(): Response
    {

        return $this->render('legal_notice.html.twig');
    }



}
