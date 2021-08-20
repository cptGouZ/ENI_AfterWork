<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Entity\User;
use App\Enums\SortieSearchOptions;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use function Doctrine\ORM\QueryBuilder;

class SortieRepository extends ServiceEntityRepository
{
    private Security $security;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Sortie::class);
        $this->security = $security;
    }

    public function getBySearch(User $user, array $options){
        //Création de la requête générale avec la limite de date à 1 mois
        $query = $this->createQueryBuilder('s')->addSelect('u')
            ->innerJoin('s.inscrits', 'u')
            //->innerJoin('s.campus', 'c')
            ->where('s.dateHeureDebut > :dateArchive')
                ->setParameter('dateArchive', new \DateTime('-1 month'))
        ;

        //Filtre sur le campus
        if( !empty( $options[SortieSearchOptions::CAMPUS] ) ) {
            $query->andWhere('s.campus = :campus')
                    ->setParameter('campus', $options[SortieSearchOptions::CAMPUS]);
        }

        //Filtre sur le nom de la sortie
        if( !empty( $options[SortieSearchOptions::NOM_CONTIENT] ) ) {
            $query->andWhere('s.nom LIKE :nom')
                ->setParameter('nom', '%'.$options[SortieSearchOptions::NOM_CONTIENT].'%');
        }

        //Filtre sur lesquels je suis organisateur
        if( !empty( $options[SortieSearchOptions::MES_SORTIES] ) ) {
            $query->andWhere('s.organisateur < :user')
                ->setParameter('user', $user);
        }

        //Filtre si une des deux checkbox inscrit/pas inscrit est coché
        if($options[SortieSearchOptions::INSCRIT_OUI] ?? false xor $options[SortieSearchOptions::INSCRIT_NON] ?? false){
            //Filtre sur les inscriptions pour lesquelles je suis inscrit
            if( $options[SortieSearchOptions::INSCRIT_OUI] ?? false) {
                $query->andWhere('u = :user')
                    ->setParameter('user', $user);
            }

            //Filtre sur les inscriptions pour lesquelles je ne suis pas inscrit
            if( $options[SortieSearchOptions::INSCRIT_NON] ?? false) {
                //Recherche des sorties où je suis inscrit pour les sortir de la requête finale
                $sortiesInscrit= $this->createQueryBuilder('s')
                    ->join('s.inscrits', 'u')
                    ->andWhere( 'u = :user' )
                        ->setParameter('user', $user)
                    ->getQuery()->getResult();
                if(!empty($sortiesInscrit)) {
                    $query->andWhere('s NOT IN (:sortiesInscrits)')
                        ->setParameter('sortiesInscrits', $sortiesInscrit);
                }
            }
        }

        //Filtre sur les sorties passées
        if( $options[SortieSearchOptions::SORTIES_PASSEES] ?? false ) {
            $query->andWhere('s.dateHeureDebut < CURRENT_TIMESTAMP()');
        }

        //Filtre sur une date de début de période
        if( !empty( $options[SortieSearchOptions::DATE_DEBUT] ) ) {
            $dateDebut=new DateTime('now');
            date_timestamp_set($dateDebut, date_timestamp_get($options[SortieSearchOptions::DATE_DEBUT]));
            $query->andWhere('s.dateHeureDebut > :dateDebutPeriode')
                ->setParameter('dateDebutPeriode', $dateDebut);
        }

        //Filtre sur une date de fin de période
        if( !empty( $options[SortieSearchOptions::DATE_FIN] ) ) {
            $dateFin=new DateTime('now');
            date_timestamp_set($dateFin, date_timestamp_get($options[SortieSearchOptions::DATE_FIN]));
            $query->andWhere('s.dateHeureDebut < :dateFinPeriode')
                ->setParameter('dateFinPeriode', $dateFin);
        }
        //dd($query->getQuery()->getSQL());
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
