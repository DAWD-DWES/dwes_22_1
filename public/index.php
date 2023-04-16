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
 * Sino si se solicita el formulario de registro
 *     Invoco la vista del formulario de registro
 * Sino si se solicita procesar el formulario de registro
 *     Leo los datos
 *     Estalezco flags de error
 *     Si hay errores
 *        Invoco la vista de formulario de registro  con información sobre los errores
 *     Sino persisto el usuario en la base de datos
 *          Invoco la vista de formulario de login 
 * Sino (En cualquier otro caso)
 *      Invoco la vista del formulario de login
 */
require "../vendor/autoload.php";

use eftec\bladeone\BladeOne;
use Dotenv\Dotenv;
use App\BD\BD;
use App\Modelo\Usuario;
use App\Dao\UsuarioDao;

session_start();
             

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$views = __DIR__ . '/../vistas';
$cache = __DIR__ . '/../cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

// Funciones de validación de datos del formulario de registro
// Validación del nombre con expresión regular
function esNombreValido(string $nombre): bool {
    return preg_match("/^\w{3,15}$/", $nombre);
}

// 
function esPasswordValido(string $clave): bool {
    return (filter_var($clave, FILTER_VALIDATE_INT) && strlen($clave) == 6);
}

function esEmailValido(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

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
    if (empty($_REQUEST) || isset($_REQUEST['botonlogin'])) {
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
// Si se solicita el formulario de registro
    } elseif (isset($_REQUEST['botonregistro'])) {
        echo $blade->run("formregistro");
        die;
// Si se solicita que se procese una petición de registro
    } elseif (isset($_REQUEST['botonprocregistro'])) {
        $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_UNSAFE_RAW));
        $clave = trim(filter_input(INPUT_POST, 'clave', FILTER_UNSAFE_RAW));
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_UNSAFE_RAW));
        $errorNombre = empty($nombre) || !esNombreValido($nombre);
        $errorPassword = empty($clave) || !esPasswordValido($clave);
        $errorEmail = empty($email) || !esEmailValido($email);
        if ($errorNombre || $errorPassword || $errorEmail) {
            echo $blade->run("formregistro", compact('nombre', 'clave', 'email', 'errorNombre', 'errorPassword', 'errorEmail'));
            die;
        } else {
            $usuario = new Usuario($nombre, $clave, $email);
            try {
                $usuarioDao->crea($usuario);
            } catch (PDOException $e) {
                echo $blade->run("formregistro", ['errorBD' => true]);
                die();
            }
            echo $blade->run("formlogin");
            die();
        }
    } else {
// Invoco la vista del formulario de login
        echo $blade->run("formlogin");
        die;
    }
}    