<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
class EtatFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        // on crée 4 auteurs avec noms et prénoms "aléatoires" en français
        $etats = Array();
        for ($i = 0; $i < 4; $i++) {
            $etats[$i] = new Etat();
            $etats[$i]->setLibelle($faker->word);
            $manager->persist($etats[$i]);
        }
        $manager->flush();
    }
}
