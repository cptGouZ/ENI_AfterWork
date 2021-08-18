<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
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
        // ETATS
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

        // VILLES
        $villes = Array();
        for ($i = 0; $i < 10; $i++) {
            $villes[$i] = new Ville();
            $villes[$i]->setNom($faker->city);
            $villes[$i]->setCodePostal($faker->citySuffix);
            $manager->persist($villes[$i]);
        }

        // LIEUX
        $lieux[] = Array();
        for ($i = 0; $i < 15; $i++) {
            $lieux[$i] = new Lieu();
            $lieux[$i]->setNom($faker->name);
            $lieux[$i]->setLatitude($faker->latitude);
            $lieux[$i]->setLongitude($faker->longitude);
            $lieux[$i]->setRue($faker->streetName);
            $lieux[$i]->setVille($villes[rand(0, count($villes)-1)]);
            $manager->persist($lieux[$i]);
        }

        // SORTIES
        $sorties[] = Array();
        for ($i = 0; $i < 15; $i++) {
            $sorties[$i] = new Sortie();
            $sorties[$i]->setNom($faker->name);
            $sorties[$i]->setCampus($campus[rand(0, count($campus)-1)]);
            $sorties[$i]->setDateHeureDebut($faker->dateTime);
            $sorties[$i]->setDateLimiteInscription($sorties[$i]->getDateHeureDebut + 5);
            $sorties[$i]->setDuree($faker->numberBetween(20, 180));
            $sorties[$i]->setEtat($etats[rand(0, count($etats)-1)]);
            $sorties[$i]->setInfosSortie($faker->text);
            $sorties[$i]->setLieu($lieux[rand(0, count($lieux)-1)]);
            $sorties[$i]->setNbInscriptionMax($faker->numberBetween(5, 12));
            $sorties[$i]->setOrganisateur($users[rand(0, count($users)-1)]);
            for ($i = 0; $i < rand(5,12); $i++){
                $sorties[$i]->addInscrit($users[rand(0, count($users)-1)]);
            }
            $manager->persist($sorties[$i]);
        }
        $manager->flush();
    }
}
