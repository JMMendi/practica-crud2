<?php

use App\Db\User;

session_start();

require __DIR__ . "/../vendor/autoload.php";

$usuarios = User::read();
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

<body class="bg-teal-200 p-4">
    <h3 class="py-2 text-center text-xl">Listado de Usuarios</h3>

    <div class="m4-4 w-3/4 mx-auto">
        <div class="flex flex-row-reverse mb-2">
            <a href="nuevo.php" class="p-2 rounded-x1 text-white bg-blue-500 hover:bg-blue-800 font-semibold"><i class="fas fa-add mr-2"></i>NUEVO</a>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Username
                    </th>

                    <th scope="col" class="px-6 py-3">
                        Perfil
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($usuarios as $item) {

                    $color = match(true) {
                        $item->getPerfil() == "Admin" => 'purple',
                        $item->getPerfil() == "Guest" => 'green',
                        default => "orange",
                    };

                    echo <<< TXT
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                            <img class="w-10 h-10 rounded-full" src="{$item->getImagen()}" alt="Imagen de perfil de {$item->getUsername()}">
                            <div class="ps-3">
                                <div class="text-base font-semibold">{$item->getUsername()}</div>
                                <div class="font-normal text-gray-500">{$item->getEmail()}</div>
                            </div>  
                        </th>

                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-2.5 w-2.5 rounded-full bg-{$color}-500 me-2">{$item->getPerfil()}</div> 
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit user</a>
                        </td>
                    </tr>
                TXT;
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>