<?php
require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

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
    <link rel="icon" href="data:,"> <!-- Evita búsqueda de favicon me daba error en inspeccionar -->
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
                    <a href="#" class="btn btn-rosa">Cesta</a>

                    <a href="login.php" class="btn btn-rosa ms-2">Iniciar Sesión</a> 
                </div>
            </div>
        </div>
    </header>

    <section>
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-5">
                <?php foreach ($resultado as $row) { ?>
                    <article class="col">
                        <div class="card shadow-sm tarjeta-producto">
                            <?php
                            $id = $row['id'];
                            $imagen = "imagenes/img/" . $id . "/zap.png";

                            if(!file_exists($imagen)){
                                $imagen = "imagenes/fotos.png";
                            }
                            ?>
                            <img src="<?php echo $imagen; ?>" alt="">
                            <div class="card-body">
                                <h5 class="card-title card-title-rosa"><?php echo $row['nombre'] ?></h5>
                                <p class="card-text card-text_letra"> $<?php echo number_format($row['precio'], 2, '.', ',' )?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="detalles_producto.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN);?>" class="btn btn-rosa" role="button">Detalles</a>
                                    </div>
                                    <a href="agregar_cesta.php" class="btn btn-rosa" role="button">Agregar a la Cesta</a>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php } ?>
            </div>
        </div>
    </section>
    <script scr="js/jquery.min.js"></script>
    <script scr="js/bootstrap.min.js"></script>
</body>
</html>
