<?php

namespace Application\Model\Cours\AdeToCoursAdapter;

require_once('./lib/StringUtil.php');
require_once('./model/ADE.php');
require_once('./model/cours/IGetCours.php');
require_once('./model/cours/Cours.php');

use Application\Model\ADE\ADE;
use Application\Lib\StringUtil\StringUtil;
use Application\Model\Cours\IGetCours\IGetCours;
use Application\Model\Cours\Cours\Cours;

class AdeToCoursAdapter
implements IGetCours {
    private ADE $ade;

    public function __construct() {
        $this->ade = new ADE();
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

        // Cour : nom
        if (array_key_exists('SUMMARY', $ade)) {
            $nom = \Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC')
                ->transliterate($ade['SUMMARY']);
            $position = strrpos($nom, ' ');

            $cours->nom = self::formatCoursNom(substr($nom, 0, 7));
        } 

        // Cour : type
        if (array_key_exists('SUMMARY', $ade)) {
            $mots = explode(' ', $ade['SUMMARY']);
            $cours->type = substr(
                end($mots),
                0,
                2
            );
        }

        // Cour : lieu
        if (array_key_exists('LOCATION', $ade)) {
            $cours->lieu = $ade['LOCATION'];
        }

        // Cour : prof
        if (array_key_exists('DESCRIPTION', $ade)) {
            // Coupe la chaine de caractère avant le nom du prof         
            $position = -1;
            for ($i = 0; $i < 4; $i++) {
                $position = strpos($ade['DESCRIPTION'], "\\n", $position + 1);

                if ($position === false) {
                    break;
                }
            }
            if ($position !== false) {
                $cours->prof = substr($ade['DESCRIPTION'], $position + 2);
            }

            // Coupe la chaine après le nom du prof
            $position = strpos($cours->prof, "\\n");
            if ($position !== false) {
                $cours->prof = substr($cours->prof, 0, $position);
            }
        }

        // Cour : groupe
        if (array_key_exists('DESCRIPTION', $ade)) { 
            // Coupe la chaine de caractère avant le groupe         
            $position = -1;
            for ($i = 0; $i < 3; $i++) {
                $position = strpos($ade['DESCRIPTION'], "\\n", $position + 1);

                if ($position === false) {
                    break;
                }
            }
            if ($position !== false) {
                $cours->groupe = substr($ade['DESCRIPTION'], $position + 2);
            }

            // Coupe la chaine après le groupe
            $position = strpos($cours->groupe, "\\n");
            if ($position !== false) {
                $cours->groupe = substr($cours->groupe, 0, $position);
            }
        }

        // Cour : date
        if (array_key_exists('DTSTART', $ade)) {
            $position = strrpos($ade['DTSTART'], 'T');

            // Coupe la chaîne à partir du T
            if ($position !== false) {
                $cours->date = substr($ade['DTSTART'], 0, $position);
            }
        }

        // Cour : heureDebut
        if (array_key_exists('DTSTART', $ade)) {
            $cours->heureDebut = $this->getHeure($ade['DTSTART']);
        }
        
        // Cour : heureDebut
        if (array_key_exists('DTEND', $ade)) {
            $cours->heureFin = $this->getHeure($ade['DTEND']);
        }

        return $cours;
    }

    private function getHeure(string $heureBrute) : string {
        $position = strrpos($heureBrute, 'T');

        // Coupe la chaîne à partir du T
        if ($position !== false) {
            $heure = substr($heureBrute, $position + 1);
        }
        
        return substr($heure, 0, 4);
    }

    private function formatCoursNom(string $nom): string {
        $nomNouveau = $nom;
        $position = 0;
        $premiereLettre = mb_substr($nom, 0, 1);

        if (!($premiereLettre == 'R' || $premiereLettre == 'S')) {
            if ($premiereLettre == 'r' || $premiereLettre == 's') {
                $nomNouveau = ucfirst($nom);
            } else if (is_numeric($premiereLettre)) {
                $nomNouveau ='R' . substr($nom, 1);
            }
        }

        if (($position = StringUtil::positionDernierChiffre($nomNouveau)) != -1) {
            return substr($nomNouveau, 0, $position + 1);
        }

        return $nomNouveau;
    }
}
