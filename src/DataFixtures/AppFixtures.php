<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use PhpParser\Node\Expr\New_;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $user = New User();
        $user->setUsername("Mundo");
        $user->setRoles(["ROLE_ENTRENADOR"]);
        $hashedPassword = $this->userPasswordHasherInterface->hashPassword($user, "Hola");
        $user->setPassword($hashedPassword);
        $manager->persist($user);
        $manager->flush();

        $user = New User();
        $user->setUsername("Nacho");
        $hashedPassword = $this->userPasswordHasherInterface->hashPassword($user, "Nacho463");
        $user->setPassword($hashedPassword);
        $manager->persist($user);
        $manager->flush();        
    }
}
