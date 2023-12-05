<?php
// https://dev-lerosie221.users.info.unicaen.fr/Jort/index.php?action=prof&annee=2023&mois=12&nom=DORBEC&prenom=paUl
// https://dev-lerosie221.users.info.unicaen.fr/Jort/index.php?action=matiere&annee=2023&mois=12&id=r1.01

require_once('./lib/Date.php');
require_once('./model/EDT.php');
require_once('./model/Module.php');
require_once('./model/SAE.php');

use Application\Lib\Date\Date;
use Application\Model\EDT\EDT;
use Application\Model\Module\Module;
use Application\Model\SAE\SAE;

if (!isset($_GET['action'])) {
    exit;
}

$edt = new EDT(creerDate());

if ($_GET['action'] === "prof") {
    $donneesJSON = array();
    $nom = (isset($_GET['nom']))? $_GET['nom']: "";
    $prenom = (isset($_GET['prenom']))? $_GET['prenom']: "";

    foreach ($edt->getHeureProf($nom . $prenom) as $moduleNom => $element) {
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

} else if ($_GET['action'] === 'matiere') {
    $donneesJSON = array();
    $id = (isset($_GET['id']))? $_GET['id']: "";

    foreach ($edt->getHeureMatiere($id) as $prof => $bilan) {
        if (!$bilan instanceof Module) {
            continue;
        }

        $donneesJSON[] = array(
            'nom' => $prof,
            'tp' => $bilan->getTp(),
            'td' => $bilan->getTd(),
            'cm' => $bilan->getCm(),
            'sae'=> $bilan->getSAE()
        );
    }

    echo json_encode($donneesJSON);
}

function creerDate(): Date {
    $annee = (isset($_GET['annee']))? intval($_GET['annee']): -1;
    $mois = (isset($_GET['mois']))? intval($_GET['mois']): -1;
    
    return new Date($annee, $mois, 1);
}
