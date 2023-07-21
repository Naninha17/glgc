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
            ->setTitle('Stratégie et digital')
            ->setContent('Spécialiste de la communication web, impression, signalétique, réseaux sociaux, agencement...')
            ->setImage('Com&Cie_HD-7.jpg')
            ->setImageUrl('https://www.cometcie.fr/')
            ->setUser( $admin )
            ;


        $quadripub = new Enseigne();

        $quadripub
            ->setTitle('Signalétique et Impression')
            ->setContent('Combine créativité et savoir-faire. Graphistes, enseignistes, webdesigners et poseurs relèvent quotidiennement vos challenges.')
            ->setImage('quadripub1.png')
            ->setImageUrl('https://www.quadripub.com/')
            ->setUser( $admin )

        ;


        $spaceetcie = new Enseigne();

        $spaceetcie
            ->setTitle('Agencement et décoration')
            ->setContent('Réinventez votre espace de vie pour un intérieur unique qui vous ressemble')
            ->setImage('Com&Cie_HD-78.jpeg')
            ->setImageUrl('')
            ->setUser( $admin )

        ;

        // Enregistrement des enseignes auprès de Doctrine
        $manager->persist($cometcie);
        $manager->persist($quadripub);
        $manager->persist($spaceetcie);


        $manager->flush();

    }
}
