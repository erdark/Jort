<?php

namespace Application\Model\Cours\AdeToCoursAdapter;

require_once('./lib/Date.php');
require_once('./lib/StringUtil.php');
require_once('./model/ADE.php');
require_once('./model/cours/IGetCours.php');
require_once('./model/cours/Cours.php');

use Application\Lib\Date\Date;
use Application\Lib\StringUtil\StringUtil;
use Application\Model\ADE\ADE;
use Application\Model\Cours\IGetCours\IGetCours;
use Application\Model\Cours\Cours\Cours;

class AdeToCoursAdapter
implements IGetCours {
    private ADE $ade;

    public function __construct(Date $date) {
        $this->ade = new ADE(
            $date,
            new Date($date->getAnnee(), $date->getMois(), $date->getNbJourMois())
        );
    }

    public function getCours(array $resources): array {
        $coursADE = [];
        $cours = [];

        foreach ($resources as $resource) {
            $coursADE = array_merge($coursADE, $this->ade->getData($resource));
        }

        foreach ($coursADE as $courADE) {
            $cour = $this->convertirAdeVersCours($courADE);
            if (!$cour->in_array($cours)) {
                $cours[] = $cour;
            }
        }

        return $cours;
    }

    public function convertirAdeVersCours(array $ade): Cours {
        $cours = new Cours();

        if (array_key_exists('SUMMARY', $ade)) {
            $cours->nom = $this->extraireNom($ade['SUMMARY']);

            if (mb_substr($cours->nom, 0, 3) === "SAE") {
                $cours->type = "SAE";
            } else {
                $cours->type = $this->extraireType($ade['SUMMARY']);
            }
        }

        if (array_key_exists('LOCATION', $ade)) {
            $cours->lieu = $this->extraireLieu($ade['LOCATION']);
        }

        if (array_key_exists('DESCRIPTION', $ade)) {
            $cours->prof = $this->extraireProf($ade['DESCRIPTION']);
            $cours->groupe = $this->extraireGroupe($ade['DESCRIPTION']);
        }

        if (array_key_exists('DTSTART', $ade)) {
            $cours->date = $this->extraireDate($ade['DTSTART']);
            $cours->heureDebut = $this->extraireHeure($ade['DTSTART']);
        }
        
        if (array_key_exists('DTEND', $ade)) {
            $cours->heureFin = $this->extraireHeure($ade['DTEND']);
        }

        return $cours;
    }

    private function extraireNom(string $chaine): string {
        $nom = StringUtil::sansAccents($chaine);
        $position = 0;
        $premiereLettre = mb_substr($chaine, 0, 1);

        if (!($premiereLettre == 'R' || $premiereLettre == 'S')) {
            if ($premiereLettre == 'r' || $premiereLettre == 's') {
                $nom = ucfirst($chaine);
            } else if (is_numeric($premiereLettre)) {
                $nom ='R' . substr($chaine, 1);
            } else {
                return $nom;
            }
        }
        $nom = (substr($nom, 0, 7));

        if (($position = StringUtil::positionDernierChiffre($nom)) != -1) {
            $nom = substr($nom, 0, $position + 1);
        }

        return $nom;
    }

    private function extraireType(string $chaine): string {
        $mots = explode(' ', StringUtil::sansAccents($chaine));
        $type = substr(
            end($mots),
            0,
            2
        );
        $typeADE = ["CM", "TD", "TP", "CC", "CT"];

        return (in_array($type, $typeADE)) ? $type : "CM";
    }

    private function extraireLieu(string $chaine): string {
        return $chaine;
    }

    private function extraireProf(string $chaine): string {
        $prof = "Aucun";
        $chaines = explode("\\n", $chaine);

        if (count($chaines) >= 5) {
            $prof = StringUtil::sansAccents($chaines[4]);
        }

        if (strpos($prof, 'Exported') !== false) {
            $prof = "Intervenant inconnu";
        }

        return $prof;
    }

    private function extraireGroupe(string $chaine): string {
        $groupe = "Aucun";
        $chaines = explode("\\n", $chaine);

        if (count($chaines) >= 4) {
            $groupe = $chaines[3];
        }

        return $groupe;
    }

    private function extraireDate(string $chaine): string {
        $chaines = explode('T', $chaine);

        if (count($chaines) >= 1) {
            return $chaines[0];
        } else {
            return "";
        }
    }

    private function extraireHeure(string $chaine): string {
        $chaines = explode('T', $chaine);

        if (count($chaines) >= 2) {
            return substr($chaines[1], 0, 4);
        } else {
            return "";
        }
    }
}
