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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    // Utiliser pour générer un hash (depuis version 5.3 de Symfony)
     private $passwordHasher;

     public function __construct(UserPasswordHasherInterface $passwordHasher)
     {
         $this->passwordHasher = $passwordHasher;
     }


    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        // ETATS
        $etats = Array();
        for ($i = 0; $i < 3; $i++) {
            $etats[$i] = new Etat();
            switch ($i){
                case 0 : $etats[$i]->setLibelle('created'); break;
                case 1 : $etats[$i]->setLibelle('published'); break;
                case 2 : $etats[$i]->setLibelle('cancelled'); break;
            }
            $manager->persist($etats[$i]);
        }
        $manager->flush();

        // CAMPUS
        $campus[] = Array() ;
        for ($i = 0; $i < 4; $i++) {
            $campus[$i] = new Campus();
            switch ($i){
                case 0 : $campus[$i]->setNom('Saint-Herblain'); break;
                case 1 : $campus[$i]->setNom('Niort'); break;
                case 2 : $campus[$i]->setNom('Rennes'); break;
                case 3 : $campus[$i]->setNom('Angers'); break;
            }
            $manager->persist($campus[$i]);
        }
        $manager->flush();

        // USERS
        $users[] = Array() ;

        for ($i = 0; $i < 4; $i++) {
            $users[$i] = new User();
            $users[$i]->setPseudo($faker->userName);
            $users[$i]->setNom($faker->lastName);
            $users[$i]->setPrenom($faker->firstName);
            $users[$i]->setTelephone($faker->numerify('##########'));
            $users[$i]->setEmail($faker->firstName.".".$faker->lastName."@eni-afterwork.fr");
            $users[$i]->setPassword($this->passwordHasher->hashPassword(
                             $users[$i],'0000'));
            $users[$i]->setAdministrateur($faker->boolean);
            $users[$i]->setActif($faker->boolean);
            $users[$i]->setCampus($campus[rand(0,count($campus)-1)]);
            $manager->persist($users[$i]);
        }

        $users[0] = new User();
        $users[0]->setNom("Ruiz");
        $users[0]->setPrenom("Alexandre");
        $users[0]->setPseudo("Zoto");
        $users[0]->setCampus($campus[0]);
        $users[0]->setEmail('alex.ruiz@eni-afterwork.fr');
        $users[0]->setTelephone('0123456789');
        $users[0]->setAdministrateur(false);
        $users[0]->setActif(true);
        $users[0]->setPassword(
            $this->passwordHasher->hashPassword( $users[0],'0000' )
        );
        $manager->persist($users[0]);

        $users[1] = new User();
        $users[1]->setNom("Gazeau");
        $users[1]->setPrenom("Julien");
        $users[1]->setPseudo("GouZ");
        $users[1]->setCampus($campus[0]);
        $users[1]->setEmail('julien.gazeau@eni-afterwork.fr');
        $users[1]->setTelephone('9876543210');
        $users[1]->setAdministrateur(false);
        $users[1]->setActif(true);
        $users[1]->setPassword(
            $this->passwordHasher->hashPassword( $users[1],'0000' )
        );
        $manager->persist($users[1]);

        $manager->flush();

        // VILLES

//        for ($i = 0; $i < 10; $i++) {
//            $villes[$i] = new Ville();
//            $villes[$i]->setNom($faker->city);
//            $villes[$i]->setCodePostal($faker->numerify('#####'));
//            $manager->persist($villes[$i]);
//        }

        $villes = Array();

        $villes[0] = new Ville();
        $villes[0]->setNom('Saint-Herblain');
        $villes[0]->setCodePostal('44800');
        $manager->persist($villes[0]);

        $villes[1] = new Ville();
        $villes[1]->setNom('Quimper');
        $villes[1]->setCodePostal('29000');
        $manager->persist($villes[1]);

        $villes[2] = new Ville();
        $villes[2]->setNom('Niort');
        $villes[2]->setCodePostal('79000');
        $manager->persist($villes[2]);

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
        for ($i = 0; $i < 4; $i++) {
            $sorties[$i] = new Sortie();
            switch($i){
                case 0 : $sorties[$i]->setNom('Karting');
                    $sorties[$i]->setInfosSortie('Karting pour voir qui est le plus rapide');
                    break;
                case 1 : $sorties[$i]->setNom('Pedalo');
                    $sorties[$i]->setInfosSortie('Tous à l\'eau !');
                    break;
                case 2 : $sorties[$i]->setNom('Tennis');
                    $sorties[$i]->setInfosSortie('La balle ? Qu\'elle balle?');
                    break;
                case 3 : $sorties[$i]->setNom('Apéro Berlin');
                    $sorties[$i]->setInfosSortie('Le mètre de girafe c\'est pour ce soir!' );
                    break;
            }
            $sorties[$i]->setCampus($campus[rand(0, count($campus)-1)]);
            $sorties[$i]->setDateHeureDebut($faker->dateTimeInInterval('-3days', '+20days'));
            $dateLimiteInscription = date_format($sorties[$i]->getDateHeureDebut(),  'Y/m/d h:i');
            $sorties[$i]->setDateLimiteInscription( date_sub(new DateTime($dateLimiteInscription), new DateInterval('PT90M')) );
            $sorties[$i]->setDuree(rand(10, 90));
            $sorties[$i]->setEtat($etats[rand(0, count($etats)-1)]);
            $sorties[$i]->setLieu($lieux[rand(0, count($lieux)-1)]);
            $sorties[$i]->setNbInscriptionMax($faker->numberBetween(5, 12));
            $sorties[$i]->setOrganisateur($users[rand(0, count($users)-1)]);
            for ($j = 0; $j < rand(2,5); $j++){
                $sorties[$i]->addInscrit($users[rand(0, count($users)-1)]);
            }
            $manager->persist($sorties[$i]);
        }
        $manager->flush();
    }
}
