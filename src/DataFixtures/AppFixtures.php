<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Ville;
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
