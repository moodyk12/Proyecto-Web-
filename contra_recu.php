<?php
require 'config/config.php';
require 'config/database.php';
require 'clases/Cliente_Fun.php';

$db = new Database();
$con = $db->conectar();

$error = [];
$email = ""; // Inicializamos la variable para el campo email
$mensaje_exito = ''; // Variable para el mensaje de éxito



// Procesar formulario si se ha enviado
if (!empty($_POST)) {
    // Recibir los datos del formulario
    $email = trim($_POST['email']);

    // Validación
    if (esNulo([$email])) {
        $error[] = "Debe llenar todos los campos.";
    } else {
        // Si no está vacío, validar el formato del correo
        if (!esEmail($email)) {
            $error[] = "El correo electrónico no es válido.";
        }
    }

    // Si no hay errores, procesar la recuperación de contraseña
    if (count($error) == 0) {
        if (emailExiste($email, $con)) {
            $sql = $con->prepare("SELECT usuarios.id, clientes.nombres FROM usuarios INNER JOIN clientes ON usuarios.id_cliente=clientes.id WHERE clientes.email LIKE ? LIMIT 1");
            $sql->execute([$email]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $user_id = $row['id'];
            $nombres = $row['nombres'];

            // Generar el token para restablecer la contraseña karla patricia lopez moody karla patricia lopez moody
            $token = NuevaPassword($user_id, $con);

            if ($token !== null) {
                require 'clases/mailer.php';
                $mailer = new Mailer();
                $url = URL_SITE . '/restaurar_contra.php?id=' . $user_id . '&token=' . $token;
                $asunto = "Recuperar Contraseña - Bunny Vibes";
                $cuerpo = "Estimado: $nombres:<br> Para restablecer su contraseña, por favor presione el siguiente enlace: <a href='$url'>Haga clic aquí para restablecer su contraseña</a>";

                if ($mailer->email($email, $asunto, $cuerpo)) {
                    $mensaje_exito = "Se ha enviado un correo electrónico a $email con las instrucciones para restablecer tu contraseña.";
                } else {
                    $error[] = "Hubo un problema al enviar el correo. Inténtalo de nuevo.";
                }
            }
        } else {
            $error[] = "No existe una cuenta asociada con el correo.";
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
        <div class="container mt-5">
            <div class="login-container">
                <h2 class="text-center">Recuperar Contraseña</h2>
            <!-- Mensajes de éxito o error -->
            <?php if ($mensaje_exito): ?>
                <div class="alert alert-success">
                    <?php echo $mensaje_exito; ?>
                </div>
                <?php endif; ?>

                <?php if (count($error) > 0): ?>
                <div class="alert alert-danger">
                    <?php echo implode(", ", $error); ?>
                </div>
                <?php endif; ?>

                <!-- Formulario -->
                <form id="form-recuperar" method="POST" class="row g-3" autocomplete="off">
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Correo Electrónico" autocomplete="off" value="<?php echo htmlspecialchars($email); ?>">
                    </div>
                    <button type="submit" class="btn btn-cesta w-100 mt-3">Continuar</button>
                </form>
                <div class="line"></div>
                <div class="text-center">
                    <p>No tienes cuenta? <a href="registro.php" class="text-decoration-none">Regístrate aquí</a></p>
                </div>
            </div>
        </div>
    </section>
<script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>