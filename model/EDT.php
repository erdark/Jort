<?php

namespace Application\Model\EDT;

require_once('./lib/StringUtil.php');
require_once('./model/Bilan.php');
require_once('./model/cours/AdeToCoursAdapter.php');
require_once('./model/cours/Cours.php');
require_once('./model/cours/IGetCours.php');

use Application\Lib\StringUtil\StringUtil;
use Application\Model\Bilan\Bilan;
use Application\Model\Cours\AdeToCoursAdapter\AdeToCoursAdapter;
use Application\Model\Cours\Cours\Cours;
use Application\Model\Cours\IGetCours\IGetCours;

class EDT {
    private array $resources;
    private IGetCours $ade;
    private array $cours;

    public function __construct() {
        $this->resources = [
            1200,   // TP1
            1201,   // Erreur
            1204,   // TP2
            1205,   // TP3
            1208,   // TP4
            1209    // TP5
        ];
        $this->ade = new AdeToCoursAdapter();
        $this->cours = $this->ade->getCours($this->resources);
    }

    public function getCoursProf(string $prof): array {
        $coursProf = [];
        $modules = [];
        $saes = [];

        foreach ($this->cours as $index => $cour) {
            if (!$cour instanceof Cours) {
                continue;
            }

            if (StringUtil::condenser($cour->prof) === StringUtil::condenser($prof)) {
                if (mb_substr($cour->nom, 0, 1) == 'R') {
                    $modules[] = $cour;
                } elseif (mb_substr($cour->nom, 0, 1) == 'S') {
                    $saes[] = $cour;
                } else {
                    $modules[] = $cour;
                }
            } else {
                array_splice($this->cours, $index, 1);
            }
        }

        $coursProf = Bilan::fabriqueBilansModule($modules);
        $coursProf += Bilan::fabriqueBilansSAE($saes);
        
        return $coursProf;
    }

    private function trierText(array $tab): array {

        return [];
    }
    private function trierTableauObjets(array $tableau): array {
        for ($ligne = 0; $ligne < count($tableau); $ligne++) {
            for ($i = $ligne; $i < count($tableau); $i++) {
                if (!array_key_exists('index', $tableau[$i]) || !array_key_exists('valeur', $tableau[$i])) {
                    return [];
                }
                if ($tableau[$ligne]['valeur'] < $tableau[$i]['valeur']) {
                    $temp = $tableau[$i];
                    $tableau[$i] = $tableau[$ligne];
                    $tableau[$ligne] = $temp;
                }
            }
        }
        return $tableau;
    }
}
