<?php 
require 'config/config.php';


header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$datos = [];

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $token = $_POST['token'];
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    if ($token == $token_tmp) {
        if (!isset($_SESSION['cesta']['productos'])) {
            $_SESSION['cesta']['productos'] = [];
        }

        if (isset($_SESSION['cesta']['productos'][$id])) {
            $_SESSION['cesta']['productos'][$id] += 1;
        } else {
            $_SESSION['cesta']['productos'][$id] = 1;
        }

        $datos['numero'] = count($_SESSION['cesta']['productos']);
        $datos['ok'] = true;
    } else {
        $datos['ok'] = false;
        $datos['mensaje'] = "Token inválido.";
    }
} else {
    $datos['ok'] = false;
    $datos['mensaje'] = "ID no proporcionado.";
}

echo json_encode($datos);
