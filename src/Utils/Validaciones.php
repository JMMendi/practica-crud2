<?php

namespace App\Utils;


class Validaciones {
    public static function sanearCadenas(string $cadena) : string {
        return trim(htmlspecialchars($cadena));
    }
    
    public static function longitudCampoCorrecta($valor, int $min, int $max) : bool {
        if (strlen($valor) < $min || strlen($valor) > $max) {
            $_SESSION['err_username'] = "*** ERROR, el nombre de usuario tiene que tener entre $min y $max caracteres. ***";
            return false;
        }
        return true;
    }

    public static function isEmailValido($email) : bool {
        if (!filter_var(FILTER_VALIDATE_EMAIL, $email)) {
            $_SESSION['err_email'] = "*** ERROR, el email no tiene un formato válido. ***";
            return false;
        }
        return true;
    }

    public static function isPerfilValido(string $perfil) : bool {
        $perfiles = Datos::getPerfiles();

        if (!in_array($perfil, $perfiles)) {
            $_SESSION['err_perfil'] = "*** ERROR, el perfil no es válido. ***";
            return false;
        }
        return true;
    }

}
