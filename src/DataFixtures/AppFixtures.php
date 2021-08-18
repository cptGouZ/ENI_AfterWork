<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Entity\Campus;
use App\Entity\User;
use DateInterval;
use DateTime;
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
        $manager->flush();

        // CAMPUS
        $campus[] = Array() ;
        for ($i = 0; $i < 20; $i++) {
            $campus[$i] = new Campus();
            $campus[$i]->setNom($faker->city);
            $manager->persist($campus[$i]);
        }
        $manager->flush();

        // USERS
        $users[] = Array() ;
        for ($i = 0; $i < 20; $i++) {
            $users[$i] = new User();
            $users[$i]->setNom($faker->lastName);
            $users[$i]->setPrenom($faker->firstName);
            $users[$i]->setTelephone($faker->numerify('##########'));
            $users[$i]->setEmail($faker->email);
            $users[$i]->setPassword($faker->password);
            $users[$i]->setAdministrateur($faker->boolean);
            $users[$i]->setActif($faker->boolean);
            $users[$i]->setCampus($campus[rand(0,count($campus)-1)]);
            $manager->persist($users[$i]);
        }
        $manager->flush();

        // VILLES
        $villes = Array();
        for ($i = 0; $i < 10; $i++) {
            $villes[$i] = new Ville();
            $villes[$i]->setNom($faker->city);
            $villes[$i]->setCodePostal($faker->numerify('#####'));
            $manager->persist($villes[$i]);
        }
        $manager->flush();

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
        $manager->flush();

        // SORTIES
        $sorties[] = Array();
        for ($i = 0; $i < 15; $i++) {
            $sorties[$i] = new Sortie();
            $sorties[$i]->setNom($faker->name);
            $sorties[$i]->setCampus($campus[rand(0, count($campus)-1)]);
            $sorties[$i]->setDateHeureDebut($faker->dateTimeInInterval('-3months', '+15days'));
            $dateLimiteInscription = date_format($sorties[$i]->getDateHeureDebut(),  'Y/m/d h:i');
            $sorties[$i]->setDateLimiteInscription( date_sub(new DateTime($dateLimiteInscription), new DateInterval('PT90M')) );
            $sorties[$i]->setDuree(rand(10, 90));
            $sorties[$i]->setEtat($etats[rand(0, count($etats)-1)]);
            $sorties[$i]->setInfosSortie($faker->text);
            $sorties[$i]->setLieu($lieux[rand(0, count($lieux)-1)]);
            $sorties[$i]->setNbInscriptionMax($faker->numberBetween(5, 12));
            $sorties[$i]->setOrganisateur($users[rand(0, count($users)-1)]);
            for ($j = 0; $j < rand(5,12); $j++){
                $sorties[$i]->addInscrit($users[rand(0, count($users)-1)]);
            }
            $manager->persist($sorties[$i]);
        }
        $manager->flush();
    }
}
