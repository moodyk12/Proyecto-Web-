<?php
require 'config/config.php';

// Comprobamos si el usuario está logueado
$response = ['loggedIn' => isset($_SESSION['user_id'])];

echo json_encode($response);
?>
