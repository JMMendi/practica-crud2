<?php

use App\Db\User;

session_start();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header("Location:users.php");
    exit;
}

require __DIR__."/../vendor/autoload.php";

$usuario = User::read($id);
if (count($usuario) == 0) {
    header("Location:users.php");
}

$imagen = $usuario[0]->getImagen();
if (basename($imagen) != 'Capibara.jpeg') { // Basename se queda solo con el nombre. no lo anterior al nombre
    unlink($imagen); // unlink borra el fichero pasado por parámetro
}

User::delete($id);

$_SESSION['mensaje'] = "Usuario Borrado con éxito";
header("Location:users.php");
