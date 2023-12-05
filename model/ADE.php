<?php 

namespace Application\Model\ADE;

require_once('./lib/Date.php');
require_once("./lib/ICS.php");

use Application\Lib\ICS\ICS;
use Application\Lib\Date\Date;

class ADE {
    const PROXY = "http://proxy.unicaen.fr:3128";
    const URL = "http://ade.unicaen.fr:80/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=4&calType=ical";

    private Date $dateDebut;
    private Date $dateFin;

    public function __construct(Date $dateDebut, Date $dateFin) {
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
    }

    public function getData(int $resource) : array {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_PROXY, self::PROXY);
        curl_setopt($curl, CURLOPT_URL, (string) (self::URL
            . "&resources=$resource"
            . "&lastDate="
                . $this->dateFin->getAnnee() 
                . "-" . $this->dateFin->getMois() 
                . "-" . $this->dateFin->getJour()
            . "&firstDate="
                . $this->dateDebut->getAnnee() 
                . "-" . $this->dateDebut->getMois() 
                . "-" . $this->dateDebut->getJour()
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return ICS::versArray(curl_exec($curl));
    }
}
