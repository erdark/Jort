<?php

namespace Application\Controleur\Mail;

class Mail {

    public function __construct() {
        ini_set("SMTP", "smtp.unicaen.fr");
        ini_set("smtp_port", "25");
    }

    public function envoyer(string $prenom, string $nom): bool {
        $destinataire = "$prenom.$nom@etu.unicaen.fr";

        $objet = "[Mail automatique] Rappel de remplissage de vos horaires";

        $message = "
            Bonjour,
            Veuillez remplir vos horaires avant la fin de la période s'il vous plaît.
            Cliquez sur le lien pour accéder à la page : https://projet-ade-26911.bubbleapps.io/version-test/consult_ressources.

            Cordialement,
            Bot de gestion des heures
        ";

        $entete = "From: noreply@unicaen.fr";
        
        return mail(
            $destinataire,
            $objet,
            $message,
            $entete
        );
    }
}
