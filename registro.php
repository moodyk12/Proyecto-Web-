<?php
require 'config/config.php';
require 'config/database.php';
require 'clases/Cliente_Fun.php';
$db = new Database();
$con = $db->conectar();

$error = [];
$nombres = $apellidos = $email = $usuario = $password = $repassword = ""; // Inicializamos las variables para los campos
$mensaje_exito = ''; // Variable para el mensaje de éxito

if (!empty($_POST)) {
    // Recibir los datos del formulario
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    // Validación
    if (esNulo([$nombres, $apellidos, $email, $usuario, $password, $repassword])) {
        $error[] = "Debe de llenar todos los campos.";
    }
    if (!validaPassword($password, $repassword)) {
        $error[] = "Las contraseñas no coinciden.";
    }
    if (usuarioExiste($usuario, $con)) {
        $error[] = "El usuario: $usuario ya ha sido registrado.";
    }
    if (emailExiste($email, $con)) {
        $error[] = "El correo electrónico: $email ya ha sido registrado.";
    }

    // Si no hay errores, registrar el cliente y el usuario
    if (count($error) == 0) {
        $id = registraCliente([$nombres, $apellidos, $email], $con);

        if ($id > 0) {
            
            require 'clases/mailer.php';
            $mailer = new Mailer();
            $token = generarToken();
            $url = URL_SITE . '/act_cliente.php?id=' . $id . '&token=' . $token;
            $asunto = "Activar Cuenta - Bunny Vibes";
            $cuerpo = "Para finalizar con el proceso de registro: $nombres $apellidos: <br>, por favor presione el siguiente enlace:
            <a href='$url'>Haga clic aquí para activar su cuenta</a>";

            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        
            if (registraUsuario([$usuario, $pass_hash, $token, $id], $con)) {

                if ($mailer->email($email, $asunto, $cuerpo)) {
                    $mensaje_exito = "¡Registro exitoso! Se le ha enviado un mensaje a su correo elecronico $email para activar su cuenta.";
                    // Limpiar los campos si el registro fue exitoso
                    $nombres = $apellidos = $email = $usuario = $password = $repassword = '';
                } else {
                    $error[] = "Error al enviar el correo de activación.";
                }
            } else {
                $error[] = "Error al registrar su usuario.";
            }
            
        } else {
            $error[] = "Error al registrar cliente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bunny Vibes</title>
    <link rel="icon" href="data:,"> 
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <div class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a href="#" class="navbar-brand d-flex align-items-center">
                    <strong>Bunny Vibes</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarHeader">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="#" class="nav-link active">Categoría</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Contáctanos</a>
                        </li>
                    </ul>
                    <a href="checkout.php" class="btn btn-rosa">
                        Cesta <span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span>
                    </a>
                    <a href="login.php" class="btn btn-rosa ms-2">Iniciar Sesión</a> 
                </div>
            </div>
        </div>
    </header>

    <section>
        <div class="container">
            <h2>Ingresar Sus Datos</h2>
            <?php if ($mensaje_exito): ?>
                <div class="alert alert-success">
                    <?php echo $mensaje_exito; ?>
                </div>
            <?php endif; ?>
            <?php mostrarMensaje($error); ?>
            <form class="row g-3" action="registro.php" method="post" autocomplete="off">
                <div class="col-md-6">
                    <label for="nombres"><span class="text-danger">* </span> Nombres</label>
                    <input type="text" name="nombres" id="nombres" class="form-control" placeholder="Ej. Juan Carlos" value="<?php echo $nombres; ?>" >
                </div>
                <div class="col-md-6">
                    <label for="apellidos"><span class="text-danger">* </span> Apellidos</label>
                    <input type="text" name="apellidos" id="apellidos" class="form-control" placeholder="Ej. Pérez González" value="<?php echo $apellidos; ?>" >
                </div>
                <div class="col-md-6">
                    <label for="email"><span class="text-danger">* </span> Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="ejemplo@correo.com" value="<?php echo $email; ?>" >
                </div>
                <div class="col-md-6">
                    <label for="usuario"><span class="text-danger">* </span> Usuario</label>
                    <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Tu nombre de usuario" value="<?php echo $usuario; ?>" >
                </div>
                <fieldset class="col-md-12">
                    <legend><strong>Contraseña</strong></legend>
                    <div class="col-md-6">
                        <label for="password"><span class="text-danger">* </span> Contraseña</label>
                        <input type="password" name="password" id="password" class="form-control" value="<?php echo $password; ?>" >
                    </div>
                    <div class="col-md-6">
                        <label for="repassword"><span class="text-danger">* </span> Repetir Contraseña</label>
                        <input type="password" name="repassword" id="repassword" class="form-control" value="<?php echo $repassword; ?>" >
                    </div>
                </fieldset>
                <div class="col-12">
                    <button type="submit" class="btn btn-cesta">Registrar</button>
                </div>
            </form>
        </div>
    </section>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
