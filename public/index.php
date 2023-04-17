<?php
/**
 *  --- Lógica del script --- 
 * 
 * Establece conexión a la base de datos PDO
 * Si el usuario ya está validado
 *   Si se solicita cerrar la sesión
 *     Destruyo la sesión
 *     Invoco la vista del formulario de login
 *   Sino (cualquier otro caso)
 *     Redirijo al cliente al script juego.php
 * Sino
 *  Si se está solicitando el formulario de login
 *     Invoco la vista del formulario de login
 *  Sino Si se pide procesar los datos del formulario
 *            Lee los valores del formulario
 *            Si los credenciales son correctos
 *               Redirijo al cliente al script de juego con una nueva partida
 *            Sino
 *               Invoco la vista del formulario de login con el flag de error
 *  Sino (En cualquier otro caso)
 *      Invoco la vista del formulario de login
 */
require "../vendor/autoload.php";

use eftec\bladeone\BladeOne;
use Dotenv\Dotenv;
use App\BD\BD;
use App\Modelo\Usuario;
use App\DAO\UsuarioDao;

session_start();

// Inicializa el acceso a las variables de entorno

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

// Inicializa el acceso a las variables de entorno

$views = __DIR__ . '/../vistas';
$cache = __DIR__ . '/../cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

// Establece conexión a la base de datos PDO
try {
    $bd = BD::getConexion();
} catch (PDOException $error) {
    echo $blade->run("cnxbderror", compact('error'));
    die;
}

$usuarioDao = new UsuarioDao($bd);
// Si el usuario ya está validado
if (isset($_SESSION['usuario'])) {
    // Si se solicita cerrar la sesión
    if (isset($_REQUEST['botonlogout'])) {
        // Destruyo la sesión
        session_unset();
        session_destroy();
        setcookie(session_name(), '', 0, '/');
        // Invoco la vista del formulario de login
        echo $blade->run("formlogin");
        die;
    } else { // Si se solicita una nueva partida
        $usuario = $_SESSION['usuario'];
        // Redirijo al cliente al script de gestión del juego
        header("Location:juego.php?botonnuevapartida");
        die;
    }

    // Sino 
} else {
    // Si se está solicitando el formulario de login
    if ((empty($_REQUEST)) || isset($_REQUEST['botonlogin'])) {
        // Invoco la vista del formulario de login
        echo $blade->run("formlogin");
        die;
        // Si se está enviando el formulario de login con los datos
    } elseif (isset($_REQUEST['botonproclogin'])) {
        // Lee los valores del formulario
        $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_UNSAFE_RAW));
        $clave = trim(filter_input(INPUT_POST, 'clave', FILTER_UNSAFE_RAW));
        $usuario = $usuarioDao->recuperaPorCredencial($nombre, $clave);
        // Si los credenciales son correctos
        if ($usuario) {
            $_SESSION['usuario'] = $usuario;
            // Redirijo al cliente al script de juego con una nueva partida
            header("Location:juego.php?botonnuevapartida");
            die;
        }
        // Si los credenciales son incorrectos
        else {
            // Invoco la vista del formulario de login con el flag de error activado
            echo $blade->run("formlogin", ['error' => true]);
            die;
        }
        // En cualquier otro caso
    } else {
        // Invoco la vista del formulario de login
        echo $blade->run("formlogin");
        die;
    }
} 