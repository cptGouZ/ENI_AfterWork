<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Ville;
use App\Entity\Campus;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        // etats
        $etats = Array();
        for ($i = 0; $i < 4; $i++) {
            $etats[$i] = new Etat();
            $etats[$i]->setLibelle($faker->word);
            $manager->persist($etats[$i]);
        }

        // CAMPUS
        $campus[] = Array() ;
        for ($i = 0; $i < 20; $i++) {
            $campus[$i] = new Campus();
            $campus[$i]->setNom($faker->city);

            $manager->persist($campus[$i]);
        }

        // USERS
        $users[] = Array() ;
        for ($i = 0; $i < 20; $i++) {
            $users[$i] = new User();
            $users[$i]->setNom($faker->lastName);
            $users[$i]->setPrenom($faker->firstName);
            $users[$i]->setTelephone($faker->phoneNumber);
            $users[$i]->setEmail($faker->email);
            $users[$i]->setPassword($faker->password);
            $users[$i]->setAdministrateur($faker->boolean);
            $users[$i]->setActif($faker->boolean);

            $manager->persist($users[$i]);
        }

        // villes
        $villes = Array();
        for ($i = 0; $i < 4; $i++) {
            $villes[$i] = new Ville();
            $villes[$i]->setNom($faker->city);
            $villes[$i]->setCodePostal($faker->citySuffix);
            $manager->persist($villes[$i]);
        }

        //lieux
        $lieux[] = Array();
        for ($i = 0; $i < 4; $i++) {
            $lieux[$i] = new Lieu();
            $lieux[$i]->setNom($faker->name);
            $lieux[$i]->setLatitude($faker->latitude);
            $lieux[$i]->setLongitude($faker->longitude);
            $lieux[$i]->setRue($faker->streetName);
            $lieux[$i]->setVille($villes[rand(0, count($villes)-1)]);
            $manager->persist($lieux[$i]);
        }
        $manager->flush();
    }
}
