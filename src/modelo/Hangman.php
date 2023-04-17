<?php

namespace App\Modelo;

/**
 * Hangman representa una partida del juego del ahorcado
 */
class Hangman {

    /**
     * Id de la partida de ahorcado
     */
    private $id;

    /**
     * Número de errores cometidos en la partida
     */
    private $numErrores;

    /**
     * Palabra secreta usada en la partida
     */
    private $palabraSecreta;

    /**
     * Estado de la palabra según va siendo descubierta. Por ejemplo c_c_e
     */
    private $palabraDescubierta;

    /**
     * Lista de jugadas que ha realizado el jugador en la partida
     */
    private $letras;

    /**
     * Número de errores permitido en la partida
     */
    private $maxNumErrores;

    /**
     * Constructor de la clase Hangman
     * 
     * @param AlmacenPalabrasInterface $almacen Almacen de donde obtener palabras para el juego
     * @param int $maxNumErrores Número maximo de errores
     * 
     * @returns Hangman
     */
    public function __construct($almacen, $maxNumErrores) {
        $this->setPalabraSecreta(strtoupper($almacen->obtenerPalabraAleatoria()));
        // Inicializa la estado de la palabra descubierta a una secuencia de guiones, uno por letra de la palabra oculta
        $this->setPalabraDescubierta(preg_replace('/\w+?/', '_', $this->getPalabraSecreta()));
        $this->letras = "";
        $this->setNumErrores(0);
        $this->letras = "";
        $this->maxNumErrores = $maxNumErrores;
    }

    public function getId(): ?int {
        return ($this->id) ?? null;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getPalabraSecreta(): string {
        return $this->palabraSecreta;
    }

    public function setPalabraSecreta($palabra): void {
        $this->palabraSecreta = $palabra;
    }

    public function getPalabraDescubierta(): string {
        return $this->palabraDescubierta;
    }

    public function setPalabraDescubierta($palabra): void {
        $this->palabraDescubierta = $palabra;
    }

    public function getLetras(): string {
        return $this->letras;
    }

    public function setLetras($letras): void {
        $this->letras = $letras;
    }

    public function getMaxNumErrores(): ?int {
        return $this->maxNumErrores;
    }

    public function setMaxNumErrores($maxNumErrores): void {
        $this->maxNumErrores = $maxNumErrores;
    }

    public function getNumErrores(): int {
        return $this->numErrores;
    }

    public function setNumErrores($numErrores): void {
        $this->numErrores = $numErrores;
    }

    public function getUsuarioId(): ?int {
        return ($this->id) ?? null;
    }

    /**
     * Comprueba la letra elegida por el jugador, modifica el estado de la palabra descubierta y añade la letra
     * 
     * @param string $letra Letra elegida por el jugador
     * 
     * @returns string El estado de la palabra descubierta
     */
    public function compruebaLetra($letra): string {
        $nuevaPalabraDescubierta = implode(array_map(function ($letraSecreta, $letraDescubierta) use ($letra) {
                    return ((strtoupper($letra) === $letraSecreta) ? $letraSecreta : $letraDescubierta);
                }, str_split($this->getPalabraSecreta()), str_split($this->getPalabraDescubierta())));
        if ($nuevaPalabraDescubierta == $this->getPalabraDescubierta()) {
            $this->numErrores++;
        } else {
            $this->setPalabraDescubierta($nuevaPalabraDescubierta);
        }
        $this->setLetras("{$this->getLetras()}$letra");
        return ($nuevaPalabraDescubierta);
    }

    /**
     * Comprueba si la palabra oculta el juego ya ha sido descubierta
     * 
     * @returns bool Verdadero si ya ha sido descubierta y falso en caso contrario
     */
    public function esPalabraDescubierta(): bool {
        // Si ya no hay guiones en la palabra descubierta
        return (!(strstr($this->getPalabraDescubierta(), "_")));
    }

    /**
     * Comprueba si la partida se ha acabado
     * 
     * @returns bool Verdadero si ya se ha acabado y falso en caso contrario
     */
    public function esFin(): bool {
        return ($this->esPalabraDescubierta() || ($this->getNumErrores() === $this->getMaxNumErrores()));
    }

}
