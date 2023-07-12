<?php

namespace App\DataFixtures;

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

        $admin
            ->setEmail('amandine@cometcie.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword(
                $this->encoder->hashPassword($admin, 'Azerty12!')
            );

        $manager->persist($admin);
        $manager->flush();

    }
}
