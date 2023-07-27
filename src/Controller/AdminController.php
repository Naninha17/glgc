<?php

namespace App\Controller;

use App\Entity\Enseigne;
use App\Entity\LegalNotice;
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

        return $this->render('admin/liste.html.twig',[
            'enseigne' => $enseigne,
        ]);
    }

    #[Route('/enseigne/modifier/{id}', name: 'enseigne_edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function enseigneEdit(int $id, Request $request, ManagerRegistry $doctrine): Response
    {
        // Récupération de l'enseigne à partir de l'ID
        $entityManager = $doctrine->getManager();
        $enseigne = $entityManager->getRepository(Enseigne::class)->find($id);


            // Création du formulaire de modification d'enseigne
        $enseigneEdit_form = $this->createForm(EnseigneFormType::class, $enseigne);

            // Traitement du formulaire lorsqu'il est soumis
            $enseigneEdit_form->handleRequest($request);
            if ($enseigneEdit_form->isSubmitted() && $enseigneEdit_form->isValid()) {

                // Récupération du champ image du formulaire
//                $imageFile = $enseigneEdit_form->get('image')->getData();

                // Récupération de l'emplacement ou on sauvegarde toutes les photos de profil
//                $imageLocation = $this->getParameter('app.image.directory');

//                $imageFile->guessExtension();

                // Sauvegarde de la photo avec son nouvel emplacement
//                $imageFile->move(
//                    $imageLocation,
//                );

//             // Vérifier si une nouvelle image a été téléchargée
                $imageFile = $enseigneEdit_form->get('image')->getData();
                if ($imageFile != null) {
                    // Enregistrement de l'image dans le champ 'image'
                    $enseigne->setImage($imageFile->getClientOriginalName());
                }

//                 Vérifier si une nouvelle image a été téléchargée
                $imageLogo = $enseigneEdit_form->get('imageLogo')->getData();
                if ($imageLogo !== null) {
                    // Enregistrement de l'image dans le champ 'imageLogo'
                    $enseigne->setImageLogo($imageLogo->getClientOriginalName());
                }



                // Enregistrement des modifications en base de données
                $entityManager->flush();

//                // Ajout d'un message flash pour indiquer que l'enseigne a été modifiée avec succès
//                $this->addFlash('success', 'L\'enseigne a été modifiée avec succès.');

                // Redirection vers la liste des enseignes
                return $this->redirectToRoute('enseigne_list');
            }

            return $this->render('admin/edit.html.twig', [
                'enseigneEdit_form' => $enseigneEdit_form->createView(),
            ]);
    }

    /*
     *  Contrôleur servant à supprimer une enseigne via son Id passé dans l'url
     */
    #[Route('/enseigne/supprimer/{id}', name: 'enseigne_delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function enseigneDelete(Enseigne $enseigne, ManagerRegistry $doctrine, Request $request): Response
    {

        // Vérif si le token csrf est valide
        if (!$this->isCsrfTokenValid('admin_enseigne_delete' . $enseigne->getId(), $request->query->get('csrf_token') )){
            $this->addFlash('error', 'Token sécurité invalide, veuillez ré-essayer.');

        }else{

            $em = $doctrine->getManager();
            $em->remove($enseigne);
            $em->flush();

            $this->addFlash('success', 'L\'enseigne a été supprimée avec succès !');
        }

            return $this->redirectToRoute('enseigne_list');
    }


    #[Route('/admin-mentions-legales/', name: 'admin_legal')]
    #[IsGranted('ROLE_ADMIN')]
    public function legalNotice(Request $request, ManagerRegistry $doctrine): Response
    {

        // Création
        $newNotice = new LegalNotice();

        // Création d'un formulaire pour intéragir avec la page des mentions légales
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