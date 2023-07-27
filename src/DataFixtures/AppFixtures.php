<?php

namespace App\DataFixtures;

use App\Entity\Enseigne;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {

        // Création d'un compte admin
        $admin = new User();

        // Hydratation du nouvel admin
        $admin
            ->setEmail('amandine@cometcie.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword(
                $this->encoder->hashPassword($admin, 'Azerty12!')
            );

        // Enregistrement du nouvel admin auprès de Doctrine
        $manager->persist($admin);



        // Création des enseignes dans la bdd
        $cometcie = new Enseigne();

        // Hydratation des enseignes
        $cometcie
            ->setTitle('')
            ->setContent('')
            ->setImage('')
            ->setImageUrl('')
            ->setImageLogo('')
            ->setSubtitle('')
            ->setColor('')
            ->setUser( $admin )
            ;


        $quadripub = new Enseigne();

        $quadripub
            ->setTitle('')
            ->setContent('')
            ->setImage('')
            ->setImageUrl('')
            ->setImageLogo('')
            ->setSubtitle('')
            ->setColor('')
            ->setUser( $admin )

        ;


        $spaceetcie = new Enseigne();

        $spaceetcie
            ->setTitle('')
            ->setContent('')
            ->setImage('')
            ->setImageUrl('')
            ->setImageLogo('')
            ->setSubtitle('')
            ->setColor('')
            ->setUser( $admin )

        ;

//        $temeraires = new Enseigne();
//
//        $temeraires
//            ->setImage('')
//            ->setImageUrl('')
//        ->setImageLogo('')
//        ->setSubtitle('')
//            ->setUser( $admin )
//            ;

        // Enregistrement des enseignes auprès de Doctrine
        $manager->persist($cometcie);
        $manager->persist($quadripub);
        $manager->persist($spaceetcie);
//        $manager->persist($temeraires);


        $manager->flush();

    }
}
