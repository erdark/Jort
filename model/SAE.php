<?php

namespace Application\Model\SAE;

/**
 * Gère le nombre d'heures des SAE
 */
class SAE {
    private float $nbHeure;

    public function __construct() {
        $this->nbHeure = 0;
    }

    /**
     * Retourne le nombre d'heures
     * @return float
     */
    public function getNbHeure(): float {
        return $this->nbHeure;
    }

    /**
     * Ajoute des heures
     * @param float $nbHeure nombre d'heures
     */
    public function ajouterHeure(float $nbHeure) {
        $this->nbHeure += $nbHeure;
    }
}
