<?php


namespace App\Form;

use App\Entity\Enseigne;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class NewEnseigneFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('title', TextType::class, [
                    'label' => 'Titre ',
                    'required' => false, // Permet que le champ Image ne soit pas obligatoire à remplir
                    'empty_data' => '', //Nécessaire quand on souhaite modifier/supprimer, afin d'éviter d'avoir une erreur 500
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Merci de renseigner un titre',
                        ]),
                        new Length([
                            'min' => 2,
                            'max' => 150,
                            'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères',
                            'maxMessage' => 'Le titre doit contenir au maximum {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Contenu',
                'required' => false, // Permet que le champ Image ne soit pas obligatoire à remplir
                'purify_html' => true, //permet la protection des failles XSS
                'attr' => [
                    'class' => ' d-none',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un contenu',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 50000,
                        'minMessage' => 'Le contenu doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le contenu doit contenir au maximum {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('subtitle', TextType::class, [
                'label' => 'Nom du lien ',
                'required' => false, // Permet que le champ Image ne soit pas obligatoire à remplir
                'empty_data' => '', //Nécessaire quand on souhaite modifier/supprimer, afin d'éviter d'avoir une erreur 500
                'constraints' => [

                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le nom du lien doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le nom du lien doit contenir au maximum {{ limit }} caractères',
                    ]),

                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image ',
                'required' => false, // Permet que le champ Image ne soit pas obligatoire à remplir
                'attr' => [
                    'accept' => 'image/jpeg, image/png',
                ],
                'constraints' => [

                    new File([
                        'maxSize' => '5M',
                        'maxSizeMessage' => 'Fichier trop volumineux. La taille maximum autorisée est de {{ limit }} {{ suffix }}',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'L\'image doit être de type jpeg ou png',
                    ]),
                ],
                'data_class' => null, // Ajout de cette ligne pour autoriser une chaîne de caractères plutôt qu'une entité File autrement erreur symfony
            ])
            ->add('imageLogo', FileType::class, [
                'label' => 'Logo de l\'image ',
                'required' => false, // Permet que le champ Image ne soit pas obligatoire à remplir
                'attr' => [
                    'accept' => 'image/jpeg, image/png,',
                ],
                'constraints' => [

                    new File([
                        'maxSize' => '5M',
                        'maxSizeMessage' => 'Fichier trop volumineux. La taille maximum autorisée est de {{ limit }} {{ suffix }}',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'L\'image doit être de type jpeg ou png',
                    ])
                ],
                'data_class' => null, // Ajout de cette ligne pour autoriser une chaîne de caractères plutôt qu'une entité File autrement erreur symfony
            ])
            ->add('color', ChoiceType::class, [
                'label' => 'Couleur : ',
                'choices' => [
                    'Jaune' => 'yellow', //from-yellow-500/70
                    'Rouge' => 'red',
                    'Vert' => 'green',
                ],
                'expanded' => true, // pour afficher les choix sous forme de boutons radio
                'multiple' => false, // pour permettre de choisir une seule couleur
                'required' => false, // si la couleur est obligatoire, mettre true, sinon false
            ])
            ->add('imageUrl', TextType::class, [
                'label' => 'URL de l\'image ',
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Publier',
                'attr' => [
                    'class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 w-full',
                ],

            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Enseigne::class,
            // Le data_class définit l'entité associée au formulaire, il est lié à l'entité "Enseigne"
        ]);
    }

}