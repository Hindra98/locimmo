<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        /*
        $user = new Utilisateur();
        $user->setEmail('user@gmail.com');
        $user->setPassword($this->hasher->hashPassword($user,'user'));
        $user->setNom('User FI Uno');
        $user->setCreateAt();
        $user->setUpdateAt();
        $user1 = new Utilisateur();
        $user1->setEmail('user1@gmail.com');
        $user1->setPassword($this->hasher->hashPassword($user1,'user'));
        $user1->setNom('User FI Uno');
        $user1->setCreateAt();
        $user1->setUpdateAt();
        $admin = new Utilisateur();
        $admin->setEmail('admin@gmail.com');
        $admin->setPassword($this->hasher->hashPassword($admin,'admin'));
        $admin->setNom('Admin FI Uno');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setCreateAt();
        $admin->setUpdateAt();
        $admin1 = new Utilisateur();
        $admin1->setEmail('admin1@gmail.com');
        $admin1->setPassword($this->hasher->hashPassword($admin1,'admin'));
        $admin1->setNom('Admin FI Dos');
        $admin1->setRoles(['ROLE_ADMIN']);
        $admin1->setCreateAt();
        $admin1->setUpdateAt();
        $proprio = new Utilisateur();
        $proprio->setEmail('proprio@gmail.com');
        $proprio->setPassword($this->hasher->hashPassword($proprio,'proprio'));
        $proprio->setNom('Proprio FI Uno');
        $proprio->setRoles(['ROLE_PROPRIO']);
        $proprio->setCreateAt();
        $proprio->setUpdateAt();
        $proprio1 = new Utilisateur();
        $proprio1->setEmail('proprio1@gmail.com');
        $proprio1->setPassword($this->hasher->hashPassword($proprio1,'proprio'));
        $proprio1->setNom('Proprio FI Dos');
        $proprio1->setRoles(['ROLE_PROPRIO']);
        $proprio1->setCreateAt();
        $proprio1->setUpdateAt();

        $manager->persist($user1);
        $manager->persist($user);
        $manager->persist($admin);
        $manager->persist($admin1);
        $manager->persist($proprio);
        $manager->persist($proprio1);

        $manager->flush();
        */
    }

    public static function getGroups(): array
    {
        return ['user'];
    }
}
