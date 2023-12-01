<?php

namespace Application\Model\Bilan;

require_once('./model/cours/Cours.php');

use Application\Model\Cours\Cours\Cours;

class Bilan {
    private int $cm;
    private int $td;
    private int $tp;

    public static function fabriqueBilansModule(array $cours) : array {
        $cour = null;
        $bilans = [];

        foreach ($cours as $element) {
            if ($element instanceof Cours) {
                $cour = $element;
            }
            $bilans[$cour->nom][] = $cour;
        }

        foreach ($bilans as $cle => $val) {
            $bilans[$cle] = new Bilan($val);
        }

        return $bilans;
    }

    public function __construct(array $cours) {
        $this->tp = 0;
        $this->td = 0;
        $this->cm = 0;

        foreach ($cours as $element) {
            if ($element instanceof Cours) {
                $cours = $element;
            }
            switch ($cours->type) {
            case "TP":
                $this->tp += $cours->getDuree();
                break;
            case "TD":
                $this->td += $cours->getDuree();
                break;
            default:
                $this->cm += $cours->getDuree();
                break;
            }
        }
    }

    public function getCm(): int {
        return $this->cm;
    }

    public function getTd(): int {
        return $this->td;
    }

    public function getTp(): int {
        return $this->tp;
    }
}
