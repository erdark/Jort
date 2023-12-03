<?php

namespace Application\Lib\StringUtil;

class StringUtil {
    public static function condenser(string $chaine): string {
        return strtolower(str_replace(' ', '', $chaine));
    }

    public static function in_arrayStrict(string $chaine, array $tab) : bool {
        return in_array(self::condenser($chaine), array_map('self::condenser', $tab));
    }

    public static function textVersNombre(string $chaine): int {
        $nombre = "";
        foreach (str_split($chaine) as $caractere) {
            if (ctype_digit($caractere)) {
                $nombre .= $caractere;
            }
        }
        return intval($nombre);
    }

    public static function positionDernierChiffre(string $chaine): int {
        for ($i = strlen($chaine) - 1; $i >= 0; $i--) {
            if (is_numeric($chaine[$i])) {
                return $i;
            }
        }

        return -1;
    }
}
