<?php

namespace App\DAO;

use \PDO;
use App\Modelo\Usuario;

class UsuarioDAO {

    /**
     * @var $bd Conexión a la Base de Datos
     */
    private PDO $bd;

    /**
     * Constructor de la clase UsuarioDAO
     * 
     * @param PDO $bd Conexión a la base de datos
     * 
     * @returns UsuarioDAO
     */
    public function __construct(PDO $bd) {
        $this->bd = $bd;
    }
    
     /**
     * Inserta un objeto usuario en la tabla usuarios
     * 
     * @param Usuario $usuario Usuario a persistir 
     * 
     * @returns bool Resultado de la operación de inserción
     */

    function crea(Usuario $usuario): bool {
        $sql = "insert into usuarios (nombre, clave, email) values (:nombre, :clave, :email)";
        $sth = $this->bd->prepare($sql);
        $result = $sth->execute([":nombre" => $usuario->getNombre(), ":clave" => $usuario->getClave(), ":email" => $usuario->getEmail()]);
        return ($result);
    }

    public function modifica(Usuario $usuario) {
        
    }

    public function elimina(int $id) {
        
    }

    /**
     * Recupera un objeto usuario dado su nombre de usuario y clave
     * 
     * @param string $nombre Nombre de usuario
     * @param string $clave Clave del usuario
     * 
     * @returns Usuario que corresponde a ese nombre y clave o null en caso contrario
     */
    public function recuperaPorCredencial(string $nombre, string $pwd): ?Usuario {
        $this->bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        $sql = 'select * from usuarios where nombre=:nombre and clave=:pwd';
        $sth = $this->bd->prepare($sql);
        $sth->execute([":nombre" => $nombre, ":pwd" => $pwd]);
        $sth->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Usuario::class);
        $usuario = ($sth->fetch()) ?: null;
        return $usuario;
    }

}
