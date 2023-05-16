<?php

namespace App\Almacen;

class AlmacenPalabrasFichero implements AlmacenPalabrasInterface {

    /**
     * 
     * @var string Lista de palabras con las que poder jugar
     */
    private $listaPalabras;

    /**
     * Constructor de la clase AlmacenPalabrasFichero
     * 
     * Lee todas las palabras del fichero indicado en el fichero de configuración y las almacena en la propiedad $listaPalabras
     * 
     * @returns AlmacenPalabrasFichero
     */
    public function __construct() {
        $fichero = fopen($_SERVER['DOCUMENT_ROOT'] . $_ENV['RUTA_ALMACEN_PALABRAS'], 'r');
        // recorre todas las palabras y las guarda en el array $palabras
        // de forma separada
        while ($palabraFichero = fgets($fichero)) {
            $this->listaPalabras[] = trim($palabraFichero);
        }
    }

    /**
     * Obtiene una palabra aleatoria
     * 
     * 
     * @returns string Palabra aleatoria
     */
    public function obtenerPalabraAleatoria(): string {
        return $this->listaPalabras[array_rand($this->listaPalabras)];
    }

}