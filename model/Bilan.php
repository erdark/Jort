<?php

namespace Application\Model\Bilan;

require_once('./model/Module.php');
require_once('./model/cours/Cours.php');

use Application\Model\Module\Module;
use Application\Model\Cours\Cours\Cours;

/**
 * Synthétise des cours
 */
class Bilan {

    /**
     * Synthétises des cours en fonction d'un de leur atribue
     * @param array $cours listes des cours
     * @param string $element nom de l'atribue trier
     * @return array les éléments synthétisé 
     */
    public static function fabriqueBilan(array $cours, string $element): array {
        $bilans = [];

        // trie les cours par elements
        foreach ($cours as $cour) {
            if ($cour instanceof Cours && property_exists($cour, $element)) {
                $bilans[$cour->{$element}][] = $cour;
            }
        }
        
        // fabrique le bilan de chaque elements
        foreach ($bilans as $cle => $cours) {
            $module = new Module();

            // fabrique un Module pour un elements
            foreach ($cours as $cour) {
                if (!$cour instanceof Cours) {
                    continue;
                }

                switch ($cour->type) {
                case "TP":
                    $module->ajouterTp($cour->getDuree());
                    break;
                case "TD":
                    $module->ajouterTd($cour->getDuree());
                    break;
                case "SAE":
                    $module->ajouterSAE($cour->getDuree());
                    break;
                default:
                    $module->ajouterCm($cour->getDuree());
                    break;
                }
            }

            $bilans[$cle] = $module;
        }

        return $bilans;
    }
}
