<?php

namespace App\Repository;

use App\Entity\Sortie;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
abstract class SortieSearchOptions
{
    const INSCRIT_OUI = 0;
    const INSCRIT_NON = 1;
    const MES_SORTIES = 2;
    const DATE_DEBUT = 3;
    const DATE_FIN = 4;
    const SORTIES_PASSEES = 5;
    const NOM_CONTIENT = 6;
    const CAMPUS = 7;
}