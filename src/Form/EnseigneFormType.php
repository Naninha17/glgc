<?php

namespace App\Form;

use App\Entity\Enseigne;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
//use Symfony\Component\HttpFoundation\File\File; //ici
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class EnseigneFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
           ->add('title', TextType::class, [
               'label' => 'Titre',
               'empty_data' => '', //Nécessaire quand on souhaite modifier/supprimer un article, afin d'éviter d'avoir une erreur 500
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
               'purify_html' => true, //permet la protection des failles XSS
               'attr' => [
                   'class' => 'd-none',
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

//           ->add('images', FileType::class, [
//               'label' => 'Images',
//               'required' => false,
//               'multiple' => true, // Permet le téléchargement de plusieurs fichiers
//               'constraints' => [
//                   new All([ // Contrainte All englobant d'autres contraintes
//                       'constraints' => [ // Liste des contraintes à appliquer à chaque fichier
//                           new File([
//                               'maxSize' => '1M',
//                               'mimeTypes' => [
//                                   'image/jpeg',
//                                   'image/png',
//                               ],
//                               'mimeTypesMessage' => 'L\'image doit être de type jpeg ou png',
//                           ]),
//                       ],
//                   ]),
//               ],
//           ]);

           ->add('image', FileType::class, [
               'label' => 'Image',
               'required' => false,
               'multiple' => true,
               'constraints' => [
                   new File([
                       'maxSize' => '1M',
                       'mimeTypes' => [
                           'image/jpeg',
                           'image/png',
                       ],
                       'mimeTypesMessage' => 'L\'image doit être de type jpeg ou png',
                   ]),
               ],
           ])
           ->add('imageUrl', TextType::class, [
               'label' => 'URL de l\'image',
               'required' => false,
           ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
       $resolver->setDefaults([
           'data_class' => Enseigne::class,
       ]);
    }

}