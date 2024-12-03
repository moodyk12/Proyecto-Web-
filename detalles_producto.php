<?php
require 'config/config.php';
require 'config/database.php';


// Verificar si el usuario está autenticado
$usuario_autenticado = isset($_SESSION['usuario_id']) ? true : false;

$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($id === '' || $token == '') {
    echo "ID o token no válidos.";
    exit;
} else {
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    if ($token == $token_tmp) {
        // Consulta para verificar la existencia del producto
        $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1");
        $sql->execute([$id]);

        if ($sql->fetchColumn() > 0) {
            // Si existe el producto, recuperamos su información
            $sql = $con->prepare("SELECT nombre, descripcion, precio, descuento FROM productos WHERE id=? AND activo=1 LIMIT 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $nombre = $row['nombre'];
            $descripcion = $row['descripcion'];
            $precio = $row['precio'];
            $descuento = $row['descuento'];
            $precio_descuento = $precio * (1 - ($descuento / 100));

            $dir_images = 'imagenes/img/' . $id . '/';
            $rutaImg = $dir_images . 'zap.png';
            if (!file_exists($rutaImg)) {
                $rutaImg = 'imagenes/fotos.png';
            }
            $imagenes = array();
            if(file_exists($dir_images)){
                $dir = dir($dir_images);

                while (($archivo = $dir->read()) !== false) {
                    if ($archivo != 'zap.png' && (strpos($archivo, 'png') !== false || strpos($archivo, 'jpg') !== false)) {
                        $imagenes[] = $dir_images . $archivo;
                    }
                }
                $dir->close();
            } 
        } else {
            echo "Producto no encontrado.";
            exit;
        }
    } else {
        echo "Token no válido.";
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto - Bunny Vibes</title>
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
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="#" class="nav-link active">Categoría</a>
                        </li>
                    </ul>
                    <a href="checkout.php" class="btn btn-rosa me-3">
                        Cesta <span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span>
                    </a>
                    <?php  if (isset($_SESSION['user_id'])) { ?>
                                <div class="dropdown">
                                    <button class="btn btn-rosa dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?php echo $_SESSION['user_name']; ?>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="cerrar_sesion.php">Cerrar sesión</a></li>   
                                    </ul>
                                </div>
                            <?php } else { ?>
                                <a href="login.php" class="btn btn-rosa">Inicia sesión</a> 
                            <?php } ?>
                </div>
            </div>
        </div>
    </header>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img src="<?php echo $rutaImg ?>" alt="Imagen del Producto" class="d-block w-100">
                </div>
                <div class="col-md-6">
                    <h2><?php echo $nombre; ?></h2>
                    <?php if($descuento > 0 ) { ?>
                        <p><del class="price"><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></del></p>
                        <h2 class="price">
                            <?php echo MONEDA . number_format($precio_descuento, 2, '.', ','); ?>
                            <small class="discount"><?php echo $descuento; ?>% descuento</small>
                        </h2>
                    <?php } else { ?>
                        <h2 class="price"><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></h2>
                    <?php } ?>
                    <p class="lead"><?php echo $descripcion; ?></p>

                    <div class="d-grid gap-3 col-10 mx-auto">
                        <button class="btn btn-rosa" type="button" data-product-id="<?php echo $id; ?>" onclick="verificarSesionYAgregar(<?php echo $id; ?>, '<?php echo $token_tmp; ?>')">Agregar a la Cesta</button>
                        <button class="btn btn-rosa">Volver</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/app.js"></script>
</body>
</html>
