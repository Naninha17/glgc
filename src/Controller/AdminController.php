<?php

namespace App\Controller;

use App\Entity\Enseigne;
use App\Entity\LegalNotice;
use App\Form\EnseigneFormType;
use App\Form\LegalNoticeFormType;
use App\Form\NewEnseigneFormType;
use App\Repository\EnseigneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{

    /*
     * Contrôleur de page admin seulement si connecté
     */

    #[Route('/admin', name: 'admin_')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('admin/admin.html.twig');
    }

    /*
     * Contrôleur de la page qui liste toutes les enseignes
     */
    #[Route('/enseigne/liste', name:'enseigne_list')]
    #[IsGranted('ROLE_ADMIN')]
    public function liste(EnseigneRepository $enseigneRepository): Response
    {

        return $this->render('admin/liste.html.twig', [
            'enseignes' => $enseigneRepository->findAll(),
        ]);

    }


    #[Route('/ajouter-une-nouvelle-enseigne/', name: 'enseigne_new')]
    #[IsGranted('ROLE_ADMIN')]
    public function enseigneNew( Request $request, ManagerRegistry $doctrine): Response
    {
        // Récupérer toutes les enseignes depuis la base de données
//        $enseignes = $doctrine->getRepository(Enseigne::class)->find($id);

        // Création d'une nouvelle enseigne vide
        $newEnseigne = new Enseigne();

        // Création du formulaire de création de nouvelles enseignes, lié à l'enseigne vide
        $new_enseigne_form = $this->createForm(NewEnseigneFormType::class, $newEnseigne);

        // Liaison des données POST au formulaire
        $new_enseigne_form->handleRequest($request);

        // Si le formulaire a bien été envoyé et sans erreurs
        if ($new_enseigne_form->isSubmitted() && $new_enseigne_form->isValid()) {
            // On termine d'hydrater l'enseigne
            $newEnseigne->setUser($this->getUser());

            // Sauvegarde en base de données grâce au manager des entités
            $em = $doctrine->getManager();
            $em->persist($newEnseigne);
            $em->flush();

            // Redirige sur la route de la liste des enseignes
            return $this->redirectToRoute('enseigne_list', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('admin/new_enseigne.html.twig', [
            'new_enseigne_form' =>  $new_enseigne_form->createView(),
//            'enseignes' => $enseigneRepository->findAll(),
        ]);
    }

    #[Route('/enseigne/modifier/{id}', name: 'enseigne_edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function enseigneEdit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupération de l'enseigne à partir de l'ID
        $enseigne = $entityManager->getRepository(Enseigne::class)->find($id);

        // Création du formulaire de modification d'enseigne
        $enseigneEdit_form = $this->createForm(EnseigneFormType::class, $enseigne);

        // Liaison des données POST au formulaire
        $enseigneEdit_form->handleRequest($request);

        // Traitement du formulaire lorsqu'il est soumis
        if ($enseigneEdit_form->isSubmitted() && $enseigneEdit_form->isValid()) {

            // Récupération du champ image du formulaire
            $imageFile = $enseigneEdit_form->get('image')->getData();
            $imageLogo = $enseigneEdit_form->get('imageLogo')->getData();

            // Récupération de l'emplacement où on sauvegarde toutes les photos
            $imageLocation = $this->getParameter('app.admin.image.directory');
            $imageFileLocation = $this->getParameter('app.admin.image.logo_directory');

            // Vérifier si une nouvelle image a été téléchargée et la sauvegarder avec son nouvel emplacement
            if ($imageFile !== null) {
                $newFileNameImg = md5(uniqid()) . '.' . $imageFile->guessExtension();
                $imageFile->move($imageLocation, $newFileNameImg);
                $enseigne->setImage($newFileNameImg);
            }

            // Vérifier si un nouveau logo a été téléchargé et le sauvegarder avec son nouvel emplacement
            if ($imageLogo !== null) {
                $newFileNameLogo = md5(uniqid()) . '.' . $imageLogo->guessExtension();
                $imageLogo->move($imageFileLocation, $newFileNameLogo);
                $enseigne->setImageLogo($newFileNameLogo);
            }

            // Enregistrement des modifications en base de données
            $entityManager->flush();


            // Redirection vers la liste des enseignes
            return $this->redirectToRoute('enseigne_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/edit.html.twig', [
            'enseigneEdit_form' => $enseigneEdit_form->createView(),
        ]);
    }


        /*
         * Contrôleur servant à supprimer une enseigne via son Id passé dans l'url
         */
    #[Route('/enseigne/supprimer/{id}', name: 'enseigne_delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function enseigneDelete(Enseigne $enseigne, ManagerRegistry $doctrine): Response
    {

            $em = $doctrine->getManager();
            $em->remove($enseigne);
            $em->flush();

            $this->addFlash('success', 'L\'enseigne a été supprimée avec succès !');


            return $this->redirectToRoute('enseigne_list');
    }


    #[Route('/admin-mentions-legales/', name: 'admin_legal')]
    #[IsGranted('ROLE_ADMIN')]
    public function legalNotice(Request $request, ManagerRegistry $doctrine): Response
    {

        // Création
        $newNotice = new LegalNotice();

        // Création d'un formulaire pour interagir avec la page des mentions légales
        $form = $this->createForm(LegalNoticeFormType::class, $newNotice);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            // On termine d'hydrater
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