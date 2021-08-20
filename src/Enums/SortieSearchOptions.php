<?php

namespace App\Enums;

use ReflectionClass;

abstract class SortieSearchOptions
{
    const INSCRIT_OUI = 'inscrit_oui';
    const INSCRIT_NON = 'inscrit_non';
    const MES_SORTIES = 'mes_sorties';
    const DATE_DEBUT = 'date_debut';
    const DATE_FIN = 'date_fin';
    const SORTIES_PASSEES = 'sorties_passees';
    const NOM_CONTIENT = 'nom_contient';
    const CAMPUS = 'campus';


    private static $constCacheArray = NULL;
    public static function getConstants() {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = [];
        }
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }
}