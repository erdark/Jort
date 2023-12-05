<?php

namespace Application\Lib\Date;

class Date {
    private int $annee;
    private int $mois;
    private int $jour;

    public static function nbJourMois(int $mois, int $annee): int {
        return cal_days_in_month(CAL_GREGORIAN, $mois, $annee);
    }

    public function __construct(int $annee, int $mois, int $jour) {
        if ($mois < 1 || $mois > 12) {
            throw new \InvalidArgumentException("Mois doit être compris entre 1 et 12.");
        }

        $this->annee = $annee;
        $this->mois = $mois;
        $this->jour = $jour;
    }

    public function getAnnee() : int {
        return $this->annee;
    }

    public function getMois(): int {
        return $this->mois;
    }

    public function getJour(): int {
        return $this->jour;
    }

    public function getNbJourMois(): int {
        return self::nbJourMois($this->mois, $this->annee);
    }
}
