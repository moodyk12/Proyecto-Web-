<?php
require 'config/config.php';
require 'config/database.php';

$db = new Database();
$con = $db->conectar();

// Recibir el ID y el token de la URL
$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

// Si no se reciben parámetros, redirigir a la página principal
if ($id == '' || $token == '') {
    header('Location: index.php');
    exit;
}

// Función para validar el token y activar la cuenta
function validaToken($id, $token, $con)
{
    $msg = '';
    // Consultamos si el usuario existe con ese ID y token
    $sql = $con->prepare("SELECT id FROM usuarios WHERE id = ? AND token LIKE ? LIMIT 1");
    $sql->execute([$id, $token]);

    // Si se encuentra el usuario
    if ($sql->fetchColumn() > 0) {
        // Intentamos activar el usuario
        if (activarUsuario($id, $con)) {
            $msg = "Cuenta activada";
            // Redirigir al login después de activar la cuenta
            header("Location: login.php");
            exit; // Terminar ejecución para evitar más código
        } else {
            $msg = "Error al activar cuenta.";
        }
    } else {
        $msg = "No existe el registro del cliente o el token es inválido.";
    }

    return $msg;
}

// Función para activar el usuario
function activarUsuario($id, $con)
{
    // Actualizar el estado de activación a 1 y borrar el token
    $sql = $con->prepare("UPDATE usuarios SET activar = 1, token = '' WHERE id = ?");
    return $sql->execute([$id]);
}

// Llamar a la función de validación de token
$mensaje = validaToken($id, $token, $con);

?>
