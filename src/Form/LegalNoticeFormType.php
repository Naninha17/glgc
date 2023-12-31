<?php

namespace App\Form;

use App\Entity\LegalNotice;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class LegalNoticeFormType extends AbstractType
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
                       'maxMessage' => 'Le titre doit contenir au maximum {{ limit }} caractères'
                   ]),
               ],
           ])
           ->add('content', CKEditorType::class,[
               'label' => 'Contenu',
               'purify_html' => true,
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
                       'maxMessage' => 'Le contenu doit contenir au maximum {{ limit }} caractères'
                   ]),
               ],
           ])
           ->add('save', SubmitType::class, [
               'label' => 'Publier',
               'attr' => [
                   'class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full',
               ],
           ])
       ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'date_class' => LegalNotice::class,
        ]);
    }

}