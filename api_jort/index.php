<?php

require_once('./model/ADE.php');
require_once('./model/cours/Cours.php');
require_once('./model/cours/AdeToCoursAdapter.php');

use Application\Model\ADE\ADE;
use Application\Model\Cours\Cours\Cours;
use Application\Model\Cours\AdeToCoursAdapter\AdeToCoursAdapter;

?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADE</title>
</head>
<body>
<?php

$adapter = new AdeToCoursAdapter(new ADE());
$coursNoms = [];
$coursProfs = [];

foreach ($adapter->getCours() as $element) {
    if (!$element instanceof Cours) {
        continue;
    }
    $cour = $element;

    if (!in_array($cour->nom, $coursNoms)) {
        $coursNoms[] = $cour->nom;
    }
    if (!in_array($cour->prof, $coursProfs)) {
        $coursProfs[] = $cour->prof;
    }
}

foreach ($coursNoms as $nom) {
    ?> <p><?= $nom ?> <?php
}
foreach ($coursProfs as $prof) {
    ?> <p><?= $prof ?> <?php
}

?>    
</body>
</html>
