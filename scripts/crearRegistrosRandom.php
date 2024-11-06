<?php

use App\Db\User;

require __DIR__."/../vendor/autoload.php";

$cant = 0;

do {
    $cant = (int) readline("Dame la cantidad de usuarios a crear (5-50): ");
    if ($cant < 5 || $cant > 50) {
        echo "Error, la cantidad a proporcionar debe ser entre 5 y 50.";
    }
} while ($cant < 5 || $cant > 50);

User::crearRegistros($cant);
echo "se han creado los $cant usuarios.".PHP_EOL;