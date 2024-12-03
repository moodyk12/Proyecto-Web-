<?php
require 'config/config.php';
require 'config/database.php';
require 'clases/Cliente_Fun.php';
$db = new Database();
$con = $db->conectar();

$error = [];

if (!empty($_POST)) {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    if (esNulo([$usuario, $password])) {
        $error[] = "Debe de llenar todos los campos.";
    }

    if (count($error) == 0) {
        $resultadoLogin = login($usuario, $password, $con);
        if ($resultadoLogin) {
            $error[] = $resultadoLogin;
        }
    }
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <div class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a href="index.php" class="navbar-brand"><strong>Bunny Vibes</strong></a>
            </div>
        </div>
    </header>

    <section>
        <div class="container mt-5">
            <div class="login-container">
                <h2 class="text-center">Iniciar Sesión</h2>

                <?php mostrarMensaje($error); ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingresa tu usuario"  autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="contraseña" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="contraseña" name="password" placeholder="Ingresa tu contraseña"  autocomplete="off">
                    </div>
                    <div class="text-center">
                        <a href="contra_recu.php" class="text-decoration-none">¿Has olvidado la contraseña?</a>
                    </div>
                    <button type="submit" class="btn btn-cesta w-100 mt-3">Ingresar</button>
                </form>
                <div class="line"></div>
                <div class="text-center">
                    <p>No tienes cuenta? <a href="registro.php" class="text-decoration-none">Regístrate aquí</a></p>
                </div>
            </div>
        </div>
    </section>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>