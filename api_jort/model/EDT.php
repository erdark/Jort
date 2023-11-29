<?php

namespace Application\Model\EDT;

include('./model/Cours.php');
include("./model/ADE.php");

use Application\Model\ADE\ADE;

class EDT {
    private array $resources;
    private ADE $ade;

    public function __construct() {
        $this->resources = [
            1200,   // TP1
            1201,   // Erreur
            1204,   // TP2
            1205,   // TP3
            1208,   // TP4
            1209    // TP5
        ];
        $this->ade = new ADE();
    }

    public function fabriquerCours() {
        $coursADE = [];
        foreach ($this->resources as $resource) {
            $coursADE += $this->ade->getData($resource);
        }
        $cours = [];
        foreach ($coursADE as $courADE) {
            
        }
    }
}
