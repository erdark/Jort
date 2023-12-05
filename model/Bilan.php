<?php

namespace Application\Model\Bilan;

require_once('./model/Module.php');
require_once('./model/SAE.php');
require_once('./model/cours/Cours.php');

use Application\Model\Module\Module;
use Application\Model\SAE\SAE;
use Application\Model\Cours\Cours\Cours;

/**
 * Fabrique des bilans
 */
class Bilan {

    /**
     * Fabrique les bilans par cours
     * @return array
     */
    public static function fabriqueBilansModule(array $cours) : array {
        $bilans = [];

        // Regroupe les cours par module
        foreach ($cours as $cour) {
            if (!$cour instanceof Cours) {
                continue;
            }
            $bilans[$cour->nom][] = $cour;
        }

        // fabrique le bilan de chaque module
        foreach ($bilans as $cle => $cours) {
            $module = new Module();

            // fabrique un Module
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
                default:
                    $module->ajouterCm($cour->getDuree());
                    break;
                }
            }

            $bilans[$cle] = $module;
        }

        return $bilans;
    }

    public static function fabriqueBilansSAE(array $cours) : array {
        $cour = null;
        $bilans = [];

        // Regroupe les cours par sae
        foreach ($cours as $cour) {
            if (!$cour instanceof Cours) {
                continue;
            }
            $bilans[$cour->nom][] = $cour;
        }

        // fabrique le bilan de chaque sae
        foreach ($bilans as $cle => $cours) {
            $sae = new SAE();

            // fabrique une SAE
            foreach ($cours as $cour) {
                if (!$cour instanceof Cours) {
                    continue;
                }
                $sae->ajouterHeure($cour->getDuree());
            }

            $bilans[$cle] = $sae;
        }

        return $bilans;
    }

    public static function fabriqueBilansProf(array $cours) : array {
        $cour = null;
        $bilans = [];

        // Regroupe les cours par prof
        foreach ($cours as $cour) {
            if (!$cour instanceof Cours) {
                continue;
            }
            $bilans[$cour->prof][] = $cour;
        }

        // fabrique le bilan de chaque prof
        foreach ($bilans as $cle => $cours) {
            $module = new Module();

            // fabrique un Module pour un prof
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
