<?php

use App\Db\User;
use App\Utils\Datos;
use App\Utils\Validaciones;

session_start();

require __DIR__."/../vendor/autoload.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    // o no he mandado nada por id o no es un número entero
    header("Location:users.php");
    exit;
}

$usuario = User::read($id);
if (count($usuario) == 0) { // Estoy intentando editar un usauario que no existe
    header("Location:users.php");
}

$perfiles = Datos::getPerfiles();

if (isset($_POST['username'])) {
    //Procesamos formulario
    $username = Validaciones::sanearCadena($_POST['username']);
    $email = Validaciones::sanearCadena($_POST['email']);
    //$perfil= (isset($_POST['perfil'])) ? $_POST['perfil'] : -1;
    $perfil = $_POST['perfil'] ?? -1;

    $errores = false;

    if (!Validaciones::longitudCampoCorrecta($username, 4, 50)) {
        $errores = true;

    }{
        //si la longitud es correcta compruebo que no está duplicado
        if(Validaciones::existeCampo("username", $username, $id)){
            $errores=true;
        }
    }
    if (!Validaciones::isEmailValido($email)) {
        $errores = true;
    }else{
        //si el email es correcto compruebo que no está duplicado
        if(Validaciones::existeCampo("email", $email, $id)){
            $errores=true;
        }
    }
    if (!Validaciones::isPerfilValido($perfil)) {
        $errores = true;
    }

    $imagen = $usuario[0]->getImagen();
    if (is_uploaded_file($_FILES['imagen']['tmp_name'])) {
        // Si estoy aquí, el usuario subió un fichero. Ahora comprobamos si es válido.
        if (!Validaciones::isImagenValida($_FILES['imagen']['type'], $_FILES['imagen']['size'])) {
            $errores = true;
        } else {
            // Si he llegado aquí, el fichero es válido de tipo y tamaño.
            $imagen = "img/" . uniqid() . "_" . $_FILES['imagen']['name'];
            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen)) {
                $_SESSION['err_imagen'] = "*** Error no se pudo guardar la imagen en la ruta prevista. ***";
                $errores = true;
            } 
        }
    }

    

    if ($errores) {
        header("Location:update.php?id=$id");
        exit;
    }


    // Si llegamos aquí, todo es correcto y guardaremos todo
    (new User)
        ->setUsername($username)
        ->setEmail($email)
        ->setPerfil($perfil)
        ->setImagen($imagen)
        ->update($id);
    // Ahora vamos a borrar la imagen si el usuario ha subido una nueva y todo ha ido bien con la modificación.
    $imagenAntigua = $usuario[0]->getImagen();

    if ($imagenAntigua != $imagen) {
        if(basename($imagenAntigua) != 'Capibara.jpeg') {
            unlink($imagenAntigua);
        }
    }
    $_SESSION['mensaje'] = "Usuario modificado.";
    header("Location:users.php");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <!-- CDN sweetalert2 -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CDN Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body class="bg-purple-200 p-4">
    <h3 class="py-2 text-center text-xl">Editar Usuarios</h3>

    <div class="mx-auto w-2/4 rounded-x1 shadow-x1 border-2 border-black p-6">
        <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
            <div class="mb-5">
                <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                <input type="text" id="username" name="username" value="<?= $usuario[0]->getUsername() ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Username..." />
                <?php
                Validaciones::pintarError('err_username');
                ?>
            </div>

            <div class="mb-5">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                <input type="email" id="email" name="email" value="<?= $usuario[0]->getEmail() ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@flowbite.com" />
                <?php Validaciones::pintarError('err_email'); ?>

            </div>
            <div class="mb-5">
                <label for="perfil" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Perfil</label>
                <div class="flex">
                    <?php
                    foreach ($perfiles as $item) {
                        $cadena = ($usuario[0]->getPerfil()==$item) ? "checked" : "";
                        echo <<< TXT
                            
                                <input id="{$item}" type="radio" value="{$item}" name="perfil" $cadena class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="{$item}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300 mr-4">{$item}</label>
                            TXT;
                    }
                    ?>
                </div>
                <?php Validaciones::pintarError('err_perfil'); ?>

            </div>
            <div class="mb-5">
                <label for="imagen" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Imagen</label>
                <div class="flex justify-between">
                    <div>
                        <input type="file" name="imagen" accept="image/*" oninput="imgpreview.src=window.URL.createObjectURL(this.files[0])" /> <!-- Esto último es para que muestre la primera imagen que subes -->
                    </div>
                    <div class="w-full ml-8">
                        <img src="<?php $usuario[0]->getImagen() ?>" id="imgpreview" alt="imagen por defecto" class="w-56 h-56 w-full rounded object-fill">
                    </div>
                </div>
                <?php Validaciones::pintarError('err_imagen'); ?>

            </div>
            <div class="flex flex-row-reverse mb-2">
                <button type="submit" class="font-bold text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fas fa-edit mr-2"></i>EDITAR
                </button>
                <button type="reset" class="mr-2 font-bold text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fas fa-paintbrush mr-2"></i>RESET
                </button>
                <a href="users.php" class="mr-2 font-bold text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fas fa-home mr-2"></i>VOLVER
                </a>
            </div>

        </form>
    </div>
</body>

</html>