<?php

namespace App\Controller;

use App\Repository\EnseigneRepository;
use App\Repository\LegalNoticeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * Contrôleur de la page (principale et unique)
     */

    #[Route('/', name: 'main_home')]
    public function home(EnseigneRepository $enseigneRepository, ): Response
    {

        return $this->render('home.html.twig', [
             'enseigneEdit_form' => $enseigneRepository->findAll(),
        ]);
    }

    /**
     * Contrôleur de la page des mentions légales
     */

    #[Route('/mentions-legales/', name: 'legal_notice')]
    public function legalNotice(LegalNoticeRepository $legalNoticeRepository): Response
    {

        return $this->render('legal_notice.html.twig', [
            'new_legal_notice_form' => $legalNoticeRepository->findAll()
        ]);
    }



}
