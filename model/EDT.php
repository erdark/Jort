<?php

namespace Application\Model\EDT;

require_once('./lib/Date.php');
require_once('./lib/StringUtil.php');
require_once('./model/Bilan.php');
require_once('./model/cours/AdeToCoursAdapter.php');
require_once('./model/cours/Cours.php');
require_once('./model/cours/IGetCours.php');

use Application\Lib\Date\Date;
use Application\Lib\StringUtil\StringUtil;
use Application\Model\Bilan\Bilan;
use Application\Model\Cours\AdeToCoursAdapter\AdeToCoursAdapter;
use Application\Model\Cours\Cours\Cours;
use Application\Model\Cours\IGetCours\IGetCours;

class EDT {
    private array $resources;
    private IGetCours $ade;
    private array $cours;

    public function __construct(Date $date) {
        $this->resources = [
            1177,   // 1A TP1
            95403,  // 1A TP2
            1185,   // 1A TP3
            1186,   // 1A TP4
            1189,   // 1A TP5
            1191,   // 1A TP6

            1200,   // 2A TP1
            1204,   // 2A TP2
            1205,   // 2A TP3
            1208,   // 2A TP4
            1209,   // 2A TP5

            36647,  // 3A TP1
            36648,  // 3A TP2
            36649,  // 3A TP3
            36650   // 3A TP4
        ];
        $this->ade = new AdeToCoursAdapter($date);
        $this->cours = $this->ade->getCours($this->resources);
    }

    public function getHeureProf(string $prof): array {
        $coursProf = [];

        foreach ($this->cours as $cour) {
            if (
                $cour instanceof Cours
                && StringUtil::condenser($cour->prof) === StringUtil::condenser($prof)
            ) {
                $coursProf[] = $cour;
            }
        }

        return Bilan::fabriqueBilan($coursProf, "nom");
    }

    public function getHeureModule(string $nom): array {
        $coursMatiere = [];

        foreach ($this->cours as $cour) {
            if (
                $cour instanceof Cours
                && StringUtil::condenser($cour->nom) === StringUtil::condenser($nom)
            ) {
                $coursMatiere[] = $cour;
            }
        }

        return Bilan::fabriqueBilan($coursMatiere, "prof");
    }
}
