<?php

namespace App\DataFixtures;

use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class VilleFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        // on crée 4 auteurs avec noms et prénoms "aléatoires" en français
        $villes = Array();
        for ($i = 0; $i < 4; $i++) {
            $villes[$i] = new Ville();
            $villes[$i]->setNom($faker->country);
            $villes[$i]->setCodePostal($faker->countryCode);
            $manager->persist($villes[$i]);
        }
        $this->
        $manager->flush();
    }
}
