<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class SortieRepository extends ServiceEntityRepository
{
    private Security $security;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Sortie::class);
        $this->security = $security;
    }

    public function getBySearch(User $user, array $options){
//        private $sortie = new Sortie();
//        $sortie->getDateHeureFin();
        //Création de la requête générale avec la limite de date à 1 mois
        $query = $this->createQueryBuilder('s')->addSelect('u')
            ->innerJoin('s.inscrits', 'u')
            ->andWhere('s.dateHeureDebut => DATE_SUB(NOW() , INTERVAL 30 DAY)')
        ;
        //Filtre sur les inscriptions pour lesquelles je suis inscrit
        if( array_key_exists(SortieSearchOptions::INSCRIT, $options )
            && $options[SortieSearchOptions::INSCRIT] === true ) {
            $query->andWhere('u.nom = :nom')->setParameter('nom', $user->getNom());
        }
        //Filtre sur les inscriptions pour lesquelles je ne suis pas inscrit
        if( array_key_exists(SortieSearchOptions::INSCRIT, $options )
            && $options[SortieSearchOptions::INSCRIT] === false ) {
            $query->andWhere('u.nom != :nom')->setParameter('nom', $user->getNom());
        }
        //Filtre sur les sorties passées
        if( array_key_exists(SortieSearchOptions::SORTIES_PASSEES, $options )
            && $options[SortieSearchOptions::SORTIES_PASSEES] === true ) {
            $query->andWhere('s.getDateHeureFin < NOW()');
        }
        dump($query->getQuery()->getSQL());
        exit();
        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
