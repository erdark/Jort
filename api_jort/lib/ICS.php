<?php

namespace Application\Lib\ICS;

class ICS {
    public static function versArray($fichier) : array {
        $lignes = explode("\n", $fichier);
    
        $elements = array();
        $element = array();
        $cleDerniere = null;

        foreach ($lignes as $ligne) {
            $ligne = trim($ligne);

            if (empty($ligne)) {
                continue;
            }

            // Vérifier si la ligne est la continuté de la précédente
            if (!preg_match('/^[A-Z]+:/', $ligne)) {
                if ($cleDerniere !== null) {
                    $element[$cleDerniere] .= trim($ligne);
                }
            } else {
                list($cle, $valeur) = explode(':', $ligne, 2);
                $cle = strtoupper($cle);
                $valeur = trim($valeur);

                if ($cle === 'BEGIN') {
                    $element = array();
                    $cleDerniere = null;
                } elseif ($cle === 'END') {
                    $elements[] = $element;
                } else {
                    $element[$cle] = $valeur;
                    $cleDerniere = $cle;
                }
            }
        }
    
        return $elements;
    }
}
