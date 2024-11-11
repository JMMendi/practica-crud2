<?php

namespace App\Utils;

use App\Db\User;

class Validaciones {
    public static function sanearCadenas(string $cadena) : string {
        return htmlspecialchars(trim($cadena));
    }
    
    public static function longitudCampoCorrecta($valor, int $min, int $max) : bool {
        if (strlen($valor) < $min || strlen($valor) > $max) {
            $_SESSION['err_username'] = "*** ERROR, el nombre de usuario tiene que tener entre $min y $max caracteres. ***";
            return false;
        }
        return true;
    }

    public static function existeCampo(string $nomCampo, string $valorCampo) : bool {
        if (User::existeCampo($nomCampo, $valorCampo)) {
            $_SESSION['err_'.$nomCampo] = "*** Error, el $nomCampo ya existe. ***";
            return true;
        }
        return false;
    }

    public static function isEmailValido($email) : bool {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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

    public static function isImagenValida(string $type, int $size) : bool {
        if (!in_array($type, Datos::getTypeImages())) {
            $_SESSION['err_imagen'] = "*** Se esperaba un fichero de imagen. ***";
            return false;
        }
        if ($size > 2000000) {
            $_SESSION['err_imagen'] = "*** La imagen no debe exceder los 2Mb de tamaño. ***";
            return false;
        }
        return true;
    }
    public static function pintarErrores(string $error) : void {
        if (isset($_SESSION[$error])) {
            echo "<p class='mt-2 text-red-600 text-sm italic'>{$_SESSION[$error]}</p>";
            unset($_SESSION[$error]);
        }
    }
}
