<?php

namespace App\Utils;

require __DIR__."/../../vendor/autoload.php";

class Datos {
    public static function getPerfiles() : array {
        return ['Admin', 'Normal', 'Guest'];
    }

    public static function getTypeImages() : array {
        return [
            'image/gif',
            'image/png',
            'image/jpg',
            'image/bmp',
            'image/webp'
        ];
    }
}