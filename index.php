<?php
// https://dev-lerosie221.users.info.unicaen.fr/Jort/index.php?action=prof&nom=JEANPIERRE&prenom=Laurent

require_once('./model/EDT.php');
require_once('./model/Module.php');
require_once('./model/SAE.php');

use Application\Model\EDT\EDT;
use Application\Model\Module\Module;
use Application\Model\SAE\SAE;

if (!isset($_GET['action'])) {
    exit;
}

$edt = new EDT();

if ($_GET['action'] === "prof") {
    $donneesJSON = array();
    $nom;
    $prenom;

    if (!isset($_GET['nom'])) {
        $nom = "";
    } else {
        $nom = $_GET['nom'];
    }

    if (!isset($_GET['prenom'])) {
        $prenom = "";
    } else {
        $prenom = $_GET['prenom'];
    }

    foreach ($edt->getCoursProf($nom . $prenom) as $moduleNom => $element) {
        if ($element instanceof Module) {
            $donneesJSON[] = array(
                'nom' => $moduleNom,
                'tp' => $element->getTp(),
                'td' => $element->getTd(),
                'cm' => $element->getCm()
            );
        } elseif($element instanceof SAE) {
            $donneesJSON[] = array(
                'nom' => $moduleNom,
                'heure' => $element->getNbHeure()
            );
        }
    }

    echo json_encode($donneesJSON);
}
