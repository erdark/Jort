<?php

namespace Application\Model\Cours\Cours;

class Cours {
	public string $nom;
	public string $type;
    public string $prof;
	public string $groupe;
    public string $lieu;
	public string $date;
	public string $heureDebut;
	public string $heureFin;

	public function __construct() {
		$this->nom = "defaut";
		$this->type = "defaut";
		$this->prof = "defaut";
		$this->groupe = "defaut";
		$this->lieu = "defaut";
		$this->date = "defaut";
		$this->heureDebut = "defaut";
		$this->heureFin = "defaut";
	}

	/**
	 * Retourne la durée du cours
	 * @return float
	 */
	public function getDuree(): float {
		$heures = intval(substr($this->heureFin, 0, 2)) - intval(substr($this->heureDebut, 0, 2));
		$minutes = intval(substr($this->heureFin, -2)) - intval(substr($this->heureDebut, -2));

		return round(($heures + $minutes / 60), 2);
	}

	/**
	 * Indique si les objets sont égaux
	 * @return bool
	 */
	public function equals(Cours $autre): bool {
        return $this->nom === $autre->nom
			&& $this->type === $autre->type
        	&& $this->prof === $autre->prof
			&& $this->groupe === $autre->groupe
			&& $this->lieu === $autre->lieu
            && $this->date === $autre->date
            && $this->heureDebut === $autre->heureDebut
            && $this->heureFin === $autre->heureFin;
    }

	/**
	 * Indique si l'objet fait partie du tableau
	 * @return bool
	 */
	public function in_array(array $tab): bool {
		foreach ($tab as $cour) {
			if ($cour instanceof Cours && $this->equals($cour)) {
				return true;
			}
		}
		return false;
	}

}
