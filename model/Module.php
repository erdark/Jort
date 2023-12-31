<?php

namespace Application\Model\Module;

/**
 * Gère une ressources ou une sae
 */
class Module {
    private float $cm;
    private float $td;
    private float $tp;
    private float $sae;

    public function __construct() {
        $this->cm = 0;
        $this->td = 0;
        $this->tp = 0;
        $this->sae = 0;
    }

    /**
     * Renvoie cm
     * @return float
     */
    public function getCm(): float {
        return $this->cm;
    }

    /**
     * Revoie les td
     * @return float
     */
    public function getTd(): float {
        return $this->td;
    }

    /**
     * Renvoie les tp
     * @return float
     */
    public function getTp(): float {
        return $this->tp;
    }

    /**
     * Renvoie les sae
     * @return float
     */
    public function getSAE(): float {
        return $this->sae;
    }

    /**
     * ajoute des heures de cm
     * @param float nombre d'heures cm
     */
    public function ajouterCm(float $cm) {
        $this->cm += $cm;
    }

    /**
     * ajoute des heures de td
     * @param float nombre d'heures td
     */
    public function ajouterTd(float $td) {
        $this->td += $td;
    }

    /**
     * ajoute des heures de tp
     * @param float nombre d'heures tp
     */
    public function ajouterTp(float $tp) {
        $this->tp += $tp;
    }

    /**
     * Ajoute des heures de sae
     * @param float nombre d'heures sae
     */
    public function ajouterSAE(float $sae) {
        $this->sae += $sae;
    }
}
