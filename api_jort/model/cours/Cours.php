<?php

namespace Application\Model\Cours\Cours;

class Cours {
	public string $nom;
    public string $prof;
	public string $groupe;
    public string $lieu;
	public string $date;
	public string $heureDebut;
	public string $heureFin;

	public function __construct() {
		$this->nom = "defaut";
		$this->prof = "defaut";
		$this->groupe = "defaut";
		$this->lieu = "defaut";
		$this->date = "defaut";
		$this->heureDebut = "defaut";
		$this->heureFin = "defaut";
	}

	public function equals(Cours $autre): bool {
        return $this->nom === $autre->nom
        	&& $this->prof === $autre->prof
			&& $this->groupe === $autre->groupe
			&& $this->lieu === $autre->lieu
            && $this->date === $autre->date
            && $this->heureDebut === $autre->heureDebut
            && $this->heureFin === $autre->heureFin;
    }

	public function in_array(array $tab): bool {
		foreach ($tab as $cour) {
			if ($cour instanceof Cours && $this->equals($cour)) {
				return true;
			}
		}
		return false;
	}
}
