<?php

namespace App\Controller;

use App\Entity\Enseigne;
use App\Entity\LegalNotice;
use App\Entity\User;
use App\Form\EnseigneFormType;
use App\Form\LegalNoticeFormType;
use App\Repository\EnseigneRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{

    /*
     * Contrôleur de page admin seulement si connecté
     */

    #[Route('/admin', name: 'admin_')]
    public function index(): Response
        // User $user faire allumer le App\Entity\User "car erreur symfony sur la route enseigne/liste"
    {
        return $this->render('admin/admin.html.twig');
    }


    /*
     * Contrôleur de la page qui liste toutes les enseignes
     */
    #[Route('/enseigne/liste', name:'enseigne_list')]
    #[IsGranted('ROLE_ADMIN')]
    public function liste(ManagerRegistry $doctrine, EnseigneRepository $enseigneRepository): Response
    {

        $enseigne = $doctrine->getRepository(Enseigne::class);

        // $enseigne contient toutes les enseignes dans la base de données
        $enseigne = $enseigneRepository->findAll();

        dump($enseigne);

        return $this->render('admin/liste.html.twig',[
            'enseigne' => $enseigne,
        ]);
    }

    #[Route('/enseigne/modifier/{id}', name: 'enseigne_edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function enseigneEdit(int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        dump($id);

        // Récupération de l'enseigne à partir de l'ID
        $entityManager = $doctrine->getManager();
        $enseigne = $entityManager->getRepository(Enseigne::class)->find($id);

        // Vérification que l'enseigne existe
        if (!$enseigne) {
            throw $this->createNotFoundException('L\'enseigne n\'existe pas.');
        }

        // Création du formulaire de modification d'enseigne
        $form = $this->createForm(EnseigneFormType::class, $enseigne);

        // Traitement du formulaire lorsqu'il est soumis
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement des modifications en base de données
            $entityManager->flush();

            // Ajout d'un message flash pour indiquer que l'enseigne a été modifiée avec succès
            $this->addFlash('success', 'L\'enseigne a été modifiée avec succès.');

            return $this->redirectToRoute('enseigne_list');
        }

        // Redirection vers la liste des enseignes
        return $this->render('enseigne/edit.html.twig', [
            'enseigneEdit_form' => $form->createView(),
        ]);
    }




    #[Route('/admin-mentions-legales/', name: 'admin_legal')]
    #[IsGranted('ROLE_ADMIN')]
    public function legalNotice(Request $request, ManagerRegistry $doctrine): Response
    {

        // Création d'une nouvelle mention légale vide
        $newNotice = new LegalNotice();

        // Création d'un formulaire pour intéragir avec la page des mentions légales
        $form = $this->createForm(LegalNoticeFormType::class, $newNotice);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            // On termine d'hydrater l'article
            $newNotice
                ->setAuthor($this->getUser());

            // Sauvegarde en base de données grâce au manager des entités
            $em = $doctrine->getManager();
            $em->persist($newNotice);
            $em->flush();

            // Message flash de succès
            $this->addFlash('success', 'Mention légales publié avec succès !');

            // Rediriger sur la page qui montre les mentions légales
            return $this->redirectToRoute('legal_notice');

        }


        return $this->render('admin/admin_notice.html.twig', [
            'new_legal_notice_form' => $form->createView(),
        ]);
    }

}