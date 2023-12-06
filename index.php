<?php
// https://dev-lerosie221.users.info.unicaen.fr/Jort/index.php?action=prof&annee=2023&mois=11&nom=JORT&prenom=FabiEnne
// https://dev-lerosie221.users.info.unicaen.fr/Jort/index.php?action=module&annee=2023&mois=12&id=r1.01

require_once('./lib/Date.php');
require_once('./model/EDT.php');
require_once('./model/Module.php');

use Application\Lib\Date\Date;
use Application\Model\EDT\EDT;
use Application\Model\Module\Module;

if (!isset($_GET['action'])) {
    exit;
}

$edt = new EDT(new Date(
    (isset($_GET['annee']))? intval($_GET['annee']): -1, 
    (isset($_GET['mois']))? intval($_GET['mois']): -1, 
    1
));
$donneesJSON = array();

if ($_GET['action'] === "prof") {
    $nom = (isset($_GET['nom']))? $_GET['nom']: "";
    $prenom = (isset($_GET['prenom']))? $_GET['prenom']: "";

    foreach ($edt->getHeureProf($nom . $prenom) as $module => $bilan) {
        if ($bilan instanceof Module) {
            $donneesJSON[] = array(
                "nom" => $module,
                "tp" => $bilan->getTp(),
                "td" => $bilan->getTd(),
                "cm" => $bilan->getCm(),
                "sae" => $bilan->getSAE()
            );
        }
    }

} else if ($_GET['action'] === "module") {
    $id = (isset($_GET['id']))? $_GET['id']: "";

    foreach ($edt->getHeureModule($id) as $prof => $bilan) {
        if ($bilan instanceof Module) {
            $donneesJSON[] = array(
                "nom" => $prof,
                "tp" => $bilan->getTp(),
                "td" => $bilan->getTd(),
                "cm" => $bilan->getCm(),
                "sae" => $bilan->getSAE()
            );
        }
    }
}

echo json_encode($donneesJSON) . "<br>";
