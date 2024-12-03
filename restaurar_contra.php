<?php
require 'config/config.php';
require 'config/database.php';
require 'clases/Cliente_Fun.php';

$db = new Database();
$con = $db->conectar();

$error = [];
$password = ""; 
$repassword = ""; 
$mensaje_exito = ''; 

$user_id = $_GET['id'] ?? $_POST['user_id'] ?? '';
$token = $_GET['token'] ?? $_POST['token'] ?? '';

//validar las variables del id y el token 

if($user_id == '' || $token == '') {
    header("Location: index.php");
    exit;
}

if(!requestverif($user_id, $token, $con)){
    echo "No se verifico su informacion";
    exit;
};

if (!empty($_POST)) {
    // Recibir los datos del formulario
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (esNulo([$user_id, $token, $password, $repassword])) {
        $error[] = "Debe llenar todos los campos.";
    } 
    if (!validaPassword($password, $repassword)) {
        $error[] = "Las contraseñas no coinciden.";
    }
    if (count($error) == 0){
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        if(actualizarContra($user_id, $pass_hash, $con)){
            echo "Ha cambiado su contraseña";
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
                <?php if ($mensaje_exito): ?>
                <div class="alert alert-success">
                    <?php echo $mensaje_exito; ?>
                </div>
                <?php endif; ?>
                <?php mostrarMensaje($error); ?>
                <form action="restaurar_contra.php" method="POST" class="row g-3" autocomplete="off">
                    <input type="hidden" name="user_id" id="user_id" value="<?= $user_id; ?>"/>
                    <input type="hidden" name="token" id="token" value="<?= $token; ?>"/>
                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Nueva contraseña" autocomplete="off">
                    </div>

                    <div class="mb-3">
                        <label for="repassword" class="form-label">Repetir Contraseña</label>
                        <input type="password" class="form-control" id="repassword" name="repassword" placeholder="Nueva contraseña" autocomplete="off">
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