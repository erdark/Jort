<?php
// https://dev-lerosie221.users.info.unicaen.fr/api_jort/index.php?action=prof&nom=JACQUIER&prenom=Yohann

require_once('./model/EDT.php');
require_once('./model/Bilan.php');

use Application\Model\EDT\EDT;

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

    foreach ($edt->getCoursProf($nom . $prenom) as $nom => $bilan) {
        $donneesJSON[] = array(
            'nom' => $nom,
            'tp' => $bilan->getTp(),
            'td' => $bilan->getTd(),
            'cm' => $bilan->getCm()
        );
    }

    echo json_encode($donneesJSON);
}

/*
echo json_encode($bilans) . "<br>";

foreach ($bilans as $nom => $bilan) {
    echo $nom . "<br>";
    echo $bilan->getTp() . "<br>";
    echo $bilan->getTd() . "<br>";
    echo $bilan->getCm() . "<br>";
    echo "---------------------- <br>";
}*/


// Remplissage du tableau avec les données des objets


// Transformation du tableau en JSON

