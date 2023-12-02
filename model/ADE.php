<?php 

namespace Application\Model\ADE;

require_once("./lib/ICS.php");

use Application\Lib\ICS\ICS;

class ADE {
    const proxy = "http://proxy.unicaen.fr:3128";
    const url = "http://ade.unicaen.fr:80/jsp/custom/modules/plannings/anonymous_cal.jsp?projectId=4&calType=ical&";

    /**
     * Renvoie les données envoyer par l'api.
     */
    public function getData(int $resource) : array {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_PROXY, self::proxy);
        curl_setopt($curl, CURLOPT_URL, (string) (self::url .
            "resources=$resource&lastDate=2024-12-31&firstDate=2023-09-01"
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return ICS::versArray(curl_exec($curl));
    }
}