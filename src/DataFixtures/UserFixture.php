<?php

namespace App\DataFixtures;

use App\Entity\Appartements;
use App\Entity\Biens;
use App\Entity\Chambres;
use App\Entity\Maisons;
use App\Entity\Proprietaires;
use App\Entity\Studios;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        /*
        $user = new Utilisateur();
        $user->setEmail('propre@gmail.com');
        $user->setPassword($this->hasher->hashPassword($user,'propre'));
        $user->setNom('Proprietaires propre');
        $user->setCreateAt();
        $user->setUpdateAt();
        $manager->persist($user);
        $manager->flush();

        $proprio = new Proprietaires();
        $proprio->setTel(123456789);
        $proprio->setUtilisateur($user);
        $manager->persist($proprio);
        $manager->flush();
        for ($i = 0; $i < 100; $i++) {
            */
            $biens = new Biens();
            $name = $faker->lastName . ' '. $faker->firstName;
            $biens->setNom($name);
            $biens->setPrix($faker->numberBetween(110, 500));
            $biens->setSuperficie($faker->numberBetween(70, 700));
            $biens->setAdresse($faker->streetAddress);
            $da = new \DateTime();
            $ti = $da->getTimestamp() + 3600*24*30;
            $da->setTimestamp($ti);
            $biens->setDateCreation(new \DateTimeImmutable($da->format("Y-m-d H:i:s")));
            $biens->setEtat(0);
            $biens->setProprietaires(null);
            $biens->setPhoto($faker->imageUrl(200, 200, 'cats', true));

            $manager->persist($biens);
            $manager->flush();

            /*
            if ($i%4==0) {
                $studio = new Studios();
                $studio->setBiens($biens);
                $manager->persist($studio);
                $manager->flush();
            }
            if ($i%4==1) {
                $studio = new Appartements();
                $studio->setBiens($biens)->setGarage($faker->boolean())->setNbrePieces($faker->numberBetween(2, 6))
                    ->setEtage($faker->boolean())->setAscenceur($faker->boolean()) ;
                $manager->persist($studio);
                $manager->flush();
            }
            if ($i%4==2) {
                $studio = new Chambres();
                $studio->setBiens($biens)->setType('kot');
                $manager->persist($studio);
                $manager->flush();
            }
            if ($i%4==0) {
                $studio = new Maisons();
                $studio->setBiens($biens)->setGrenier($faker->boolean())->setNbrePieces($faker->numberBetween(2, 6));
                $manager->persist($studio);
                $manager->flush();
            }
        }
        */

    }
}
