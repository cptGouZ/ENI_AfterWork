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
    const INSCRIT = 0;
    const MES_SORTIES = 1;
    const SORTIES_PASSEES = 2;
    const NOM_CONTIENT = 3;
    const CAMPUS = 4;
    const DATE_DEBUT = 5;
    const DATE_FIN = 6;
}