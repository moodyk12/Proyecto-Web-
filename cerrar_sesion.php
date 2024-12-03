<?php
session_start(); // Iniciar la sesión
session_unset(); // Descartar todas las variables de sesión
session_destroy(); // Destruir la sesión

// Redirigir al usuario a la página de inicio
header("location: index.php");
exit;
?>