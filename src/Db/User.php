<?php

namespace App\Db;

use \PDO;
use \PDOException;
use App\Db\Conexion;
use App\Utils\Datos;

class User extends Conexion
{
    private int $id;
    private string $username;
    private string $email;
    private string $perfil;
    private string $imagen;

    // ----------------------

    public function create()
    {
        $q = "insert into users(username, email, perfil, imagen) values (:u, :e, :p, :i)";
        $stmt = parent::getConexion()->prepare($q);

        try {
            $stmt->execute([
                ':u' => $this->username,
                ':e' => $this->email,
                ':p' => $this->perfil,
                ':i' => $this->imagen
            ]);
        } catch (PDOException $ex) {
            throw new PDOException("Error en create: " . $ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }
    }

    public static function delete(int $id) : void {
        $q = "delete from users where id=:i";
        $stmt = parent::getConexion()->prepare($q);

        try{
            $stmt->execute([
                ':i' => $id
            ]);
        }catch(PDOException $ex){
            throw new PDOException("Error en delete: ".$ex->getMessage(), -1);
        }finally{
            parent::cerrarConexion();
        }
        
    }

    public static function read(?int $id=null) : array {
        $q = ($id === null) ? "select * from users order by id desc" : "select * from users where id=:i";
        $stmt = parent::getConexion()->prepare($q);

        try {
            ($id === null) ? $stmt->execute() : $stmt->execute([':i' => $id]);
        } catch (PDOException $ex) {
            throw new PDOException("Error en read: " . $ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS, User::class);
    }


    public function update(int $id) {
        $q = "update users set username=:u, email=:e, imagen=:i, perfil=:p where id=:id";
        $stmt = parent::getConexion()->prepare($q);
        
        try {
            $stmt->execute([
                ':u' => $this->username,
                ':e' => $this->email,
                ':p' => $this->perfil,
                ':i' => $this->imagen,
                ':id' => $id
                
            ]);
        } catch (PDOException $ex) {
            throw new PDOException("Error en update: " . $ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS, User::class);
    }

    public static function existeCampo(string $nombre, string $valor, ?int $id=null):bool{
        $q = ($id === null) ? "select count(*) as total from users where $nombre=:v" : "select count(*) as total from users where $nombre=:v AND id <> :i";
        $stmt=parent::getConexion()->prepare($q);
        $parametros = ($id === null) ? [':v' => $valor] : [':v' => $valor, ':i' => $id];

        try{
            $stmt->execute($parametros);
        }catch(PDOException $ex){
            throw new PDOException("Error en crear: ".$ex->getMessage(), -1);
        }finally{
            parent::cerrarConexion();
        }
        $filas=$stmt->fetchAll(PDO::FETCH_OBJ)[0]->total; //un array que puede estar o no vacio
        return $filas; // si devuelve cero

    }
    

    // ----------------------------------
    public static function crearRegistros(int $cantidad): void
    {

        $faker = \Faker\Factory::create("es_ES");
        $faker->addProvider(new \Mmo\Faker\FakeimgProvider($faker));

        for ($i = 0; $i < $cantidad; $i++) {
            $username = $faker->unique()->Username();
            $texto=strtoupper(substr($username,0,2));
            $email = $username."@".$faker->freeEmailDomain(); //Coge el usuario, le pone un @ y le añade solo el dominio, por ejemplo gmail.com o hotmail (Mirar documentación)
            $perfil = $faker->randomElement(Datos::getPerfiles());
            $imagen = "img/". $faker->fakeImg(dir: './../public/img', width : 640, height : 480 , fullPath : false, text : $texto,
             backgroundColor: \Mmo\Faker\FakeimgUtils::createColor(random_int(0,255), random_int(0,255), random_int(0,255)));
            
            (new User)
                ->setUsername($username)
                ->setEmail($email)
                ->setPerfil($perfil)
                ->setImagen($imagen)
                ->create();
            ;
        }
    }


    /**
     * Get the value of username
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set the value of username
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of perfil
     */
    public function getPerfil(): string
    {
        return $this->perfil;
    }

    /**
     * Set the value of perfil
     */
    public function setPerfil(string $perfil): self
    {
        $this->perfil = $perfil;

        return $this;
    }

    /**
     * Get the value of imagen
     */
    public function getImagen(): string
    {
        return $this->imagen;
    }

    /**
     * Set the value of imagen
     */
    public function setImagen(string $imagen): self
    {
        $this->imagen = ($imagen === null) ? 'img/Capibara.jpeg' : $imagen;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
