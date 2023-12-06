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
            $cours->type = $this->extraireType($ade['SUMMARY']);
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

        if (/*strpos($cours->nom, "SAE") !== false &&*/ $cours->nom == "R1.05") {
            echo $cours->nom . "<br>";
            echo $ade['DESCRIPTION'] . "<br>";
            echo $ade['SUMMARY'] . "<br>";
            echo $cours->prof . "<br>";
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
        $chaine = StringUtil::sansAccents($chaine);
        $typeADE = ["CM", "TD", "TP", "CC", "CT"];

        if (strpos($chaine, "SAE") !== false) {
            return "SAE";
        } else {
            $mots = explode(' ', $chaine);
            $type = substr(
                end($mots),
                0,
                2
            );
            return (in_array($type, $typeADE)) ? $type : "CM";
        }
    }

    private function extraireLieu(string $chaine): string {
        return $chaine;
    }

    private function extraireProf(string $chaine): string {
        $prof = "";

        // Coupe la chaine de caractère avant le nom du prof         
        $position = -1;
        for ($i = 0; $i < 4; $i++) {
            $position = strpos($chaine, "\\n", $position + 1);

            if ($position === false) {
                break;
            }
        }
        if ($position !== false) {
            $prof = StringUtil::sansAccents(substr($chaine, $position + 2));
        }

        // Coupe la chaine après le nom du prof
        $position = strpos($prof, "\\n");
        if ($position !== false) {
            $prof = substr($prof, 0, $position);
        }

        if (strrpos($prof, 'Exported') !== false) {
            $prof = "Intervenant inconue";
        }

        return $prof;
    }

    private function extraireGroupe(string $chaine): string {
        $groupe = "";

        // Coupe la chaine de caractère avant le groupe         
        $position = -1;
        for ($i = 0; $i < 3; $i++) {
            $position = strpos($chaine, "\\n", $position + 1);

            if ($position === false) {
                break;
            }
        }
        if ($position !== false) {
            $groupe = substr($chaine, $position + 2);
        }

        // Coupe la chaine après le groupe
        $position = strpos($groupe, "\\n");
        if ($position !== false) {
            $groupe = substr($groupe, 0, $position);
        }

        return $groupe;
    }

    private function extraireDate(string $chaine): string {
        $date = "";
        $position = strrpos($chaine, 'T');

        // Coupe la chaîne à partir du T
        if ($position !== false) {
            $date = substr($chaine, 0, $position);
        }

        return $date;
    }

    private function extraireHeure(string $chaine): string {
        $heure = "";
        $position = strrpos($chaine, 'T');

        // Coupe la chaîne à partir du T
        if ($position !== false) {
            $heure = substr($chaine, $position + 1);
        }
        
        return substr($heure, 0, 4);
    }
}
