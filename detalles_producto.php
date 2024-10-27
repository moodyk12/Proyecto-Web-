<?php

require 'config/config.php';
require 'config/database.php';
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
        $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1");
        $sql->execute([$id]);

        if ($sql->fetchColumn() > 0) {
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
        }
    } else {
        echo "ID o token no válidos.";
        exit;
    }
}

$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

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
                    <a href="cesta.php" class="btn btn-rosa">
                        Cesta <span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span>
                    </a>
                    <a href="login.php" class="btn btn-rosa ms-2">Iniciar Sesión</a> 
                </div>
            </div>
        </div>
    </header>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-6 order-md-1">
                    <div id="carouselImages" class="carousel slide">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="<?php echo $rutaImg ?>" alt="" style="width: 100%; height: auto;" class="d-block w-100">
                            </div>
                            <?php foreach($imagenes as $img) { ?>
                                <div class="carousel-item">
                                    <img src="<?php echo $img ?>" alt="" style="width: 100%; height: auto;" class="d-block w-100">
                                </div>
                            <?php } ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <div class="col-md-6 order-md-2">
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
                        <button class="btn btn-rosa" type="button" onclick="addProducto(<?php echo $id; ?>, '<?php echo $token_tmp?>')"> Agregar A la Cesta</button>
                        <button class="btn btn-rosa">Volver</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/app.js"></script>
   

    <!-- NUEVO POR SI NO FUNCIONA LO BORRO -->
   
</body>
</html>
