<?php

namespace App\Db;

use \PDO;
use \PDOException;

class Conexion
{
    private static ?PDO $conexion = null;

    protected static function getConexion(): ?PDO
    {
        if (self::$conexion === null) {
            self::setConexion();
        }
        return self::$conexion;
    }

    private static function setConexion(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . "/../../");
        $dotenv->load();
        // Por regla general te pone el __DIR__, como está buscando el .env, hay que mapearlo poniendo donde está

        // Cargamos las variables del archivo de .env

        $usuario = $_ENV['USUARIO'];
        $host = $_ENV['HOST'];
        $port = $_ENV['PORT'];
        $database = $_ENV['DATABASE'];
        $password = $_ENV['PASSWORD'];

        // Creamos el dsn (descriptor de nombre de servicio), en nuestro caso para mysql
        $dsn = "mysql:dbname=$database; port=$port; host=$host; charset=utf8mb4;"; // utf8mb4 es para también incluir emoticonos
        $option = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Este solo cuando todavía estamos haciendo pruebas
            PDO::ATTR_PERSISTENT => true
        ];

        try {
            self::$conexion = new PDO($dsn, $usuario, $password, $option);
        } catch (PDOException $ex) {
            throw new PDOException("Error en la conexión: {$ex->getMessage()}", -1);
        }
    }
    protected static function cerrarConexion(): void
    {
        self::$conexion = null;
    }
}

