<?php

namespace App\Utils;

require __DIR__."/../../vendor/autoload.php";

class Datos {
    public static function getPerfiles() : array {
        return ['Admin', 'Normal', 'Guest'];
    }
}