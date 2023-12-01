<?php

namespace Application\Model\EDT;

require_once('./lib/StringUtil.php');
require_once('./model/ADE.php');
require_once('./model/Bilan.php');
require_once('./model/cours/AdeToCoursAdapter.php');
require_once('./model/cours/Cours.php');
require_once('./model/cours/IGetCours.php');

use Application\Lib\StringUtil\StringUtil;
use Application\Model\ADE\ADE;
use Application\Model\Bilan\Bilan;
use Application\Model\Cours\AdeToCoursAdapter\AdeToCoursAdapter;
use Application\Model\Cours\Cours\Cours;
use Application\Model\Cours\IGetCours\IGetCours;

class EDT {
    private array $resources;
    private IGetCours $ade;
    private array $cours;

    public function __construct() {
        $this->resources = [
            1200,   // TP1
            1201,   // Erreur
            1204,   // TP2
            1205,   // TP3
            1208,   // TP4
            1209    // TP5
        ];
        $this->ade = new AdeToCoursAdapter(new ADE());
        $this->cours = $this->ade->getCours($this->resources);
    }

    public function getCoursProf(string $prof): array {
        $modules = [];
        $saes = [];
        $autres = [];

        foreach ($this->cours as $index => $cour) {
            if (!$cour instanceof Cours) {
                continue;
            }

            if (StringUtil::condenser($cour->prof) === StringUtil::condenser($prof)) {
                if (mb_substr($cour->nom, 0, 1) == 'R') {
                    $modules[] = $cour;
                } elseif (mb_substr($cour->nom, 0, 1) == 'S') {
                    $saes[] = $cour;
                } else {
                    $autres[] = $cour;
                }
            } else {
                array_splice($this->cours, $index, 1);
            }
        }
        
        return Bilan::fabriqueBilansModule($modules);
    }

    private function trierText(array $tab): array {

        return [];
    }
    private function trierTableauObjets(array $tableau): array {
        for ($ligne = 0; $ligne < count($tableau); $ligne++) {
            for ($i = $ligne; $i < count($tableau); $i++) {
                if (!array_key_exists('index', $tableau[$i]) || !array_key_exists('valeur', $tableau[$i])) {
                    return [];
                }
                if ($tableau[$ligne]['valeur'] < $tableau[$i]['valeur']) {
                    $temp = $tableau[$i];
                    $tableau[$i] = $tableau[$ligne];
                    $tableau[$ligne] = $temp;
                }
            }
        }
        return $tableau;
    }
}

/*
// On remplie le fichier avec le tableau trié.
int tab_trier[TAB_SCORE_NBL][TAB_SCORE_NBC];
for(int i = 0; i < nbl; i++){
    tab_trier[i][0] = i;
    tab_trier[i][1] = atoi(&tab_scores[i][1][0]);
}

trier_tableau_2D(tab_trier, nbl, 1);

FILE *fichier_b = fopen(TABLEAU_SCORE, "w");
if(fichier_b == NULL)
    printf("Le fichier n'a pas pu être ouvert.\n(extension.c -décompte_points)\n");
    
for(int i = 0; i < nbl; i++){
    int l = tab_trier[i][0];
    fprintf(fichier_b, "%s %s\n"
    , &tab_scores[ l ][ 0 ][ 0 ]
    , &tab_scores[ l ][ 1 ][ 0 ]);
}

void trier_tableau_2D(int tab[][DIM2], int nbl){
	for (int ligne = 0; ligne < nbl; ligne++) {
		for (int i = ligne; i < nbl; i++) {
			if (tab[ligne][1] < tab[i][1]) {
				for(int c = 0; c < DIM2; c++){
					int t = tab[i][c];
					tab[i][c] = tab[ligne][c];
					tab[ligne][c] = t;
				}
			}
		}
	}
}

trier
tab = [{index, score}, ...]

*/
