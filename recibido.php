<?php
require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$id_transaccion = isset($_GET['key']) ? $_GET['key'] : '0';

$error = '';
if($id_transaccion == '') {
    $error = 'Error';
} else {
    // Verificar si la compra existe y está completada
    $sql = $con->prepare("SELECT count(id) FROM compra WHERE id_transaccion=? AND status=?");
    $sql->execute([$id_transaccion, 'COMPLETED']);

    if ($sql->fetchColumn() > 0) {
        // Obtener detalles de la compra
        $sql = $con->prepare("SELECT id, fecha, email, total FROM compra WHERE id_transaccion=? AND status=? LIMIT 1");
        $sql->execute([$id_transaccion, 'COMPLETED']);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        $idCompra = $row['id']; // Asegúrate de usar idCompra
        $total = $row['total'];
        $fecha = $row['fecha'];

        // Obtener detalles de los productos de la compra
        $sqlDet = $con->prepare("SELECT nombre, precio, cantidad FROM detalle_compra WHERE id_compra = ?");
        $sqlDet->execute([$idCompra]); 
    } else {
        $error = 'Error al comprobar la compra';
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css"> <!-- Tus estilos propios -->
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
            <?php if(strlen($error) > 0) { ?>
            <div class="row">
                <div class="col">
                    <div class="error-message" style="font-size: 1.5rem; color: #e74c3c; text-align: center;"><?php echo $error; ?></div>
                </div>
            </div>
            <?php } else { ?>
                <div class="card">
                    <div class="card-header" style="background-color: #F7C6D7; color: #333; font-weight: bold;">
                        <b>Detalles de la Compra</b>
                    </div>
                    <div class="card-body">
                        <p><b>ID de Transacción:</b> <?php echo $id_transaccion; ?></p>
                        <p><b>Fecha de la Compra:</b> <?php echo $fecha; ?></p>
                        <p><b>Total:</b> <?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header" style="background-color: #F7C6D7; color: #333; font-weight: bold;">
                        <b>Detalles de los Productos</b>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" style="background-color: white;">
                            <thead style="background-color: #F7C6D7; color: #333;">
                                <tr>
                                    <th>Cantidad</th>
                                    <th>Producto</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row_det = $sqlDet->fetch(PDO::FETCH_ASSOC)) {
                                    $importe = $row_det['precio'] * $row_det['cantidad']; ?>
                                    <tr>
                                        <td><?php echo $row_det['cantidad']; ?></td>
                                        <td><?php echo $row_det['nombre']; ?></td>
                                        <td><?php echo MONEDA . number_format($importe, 2, '.', ','); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>

    <footer class="card-footer" style="background-color: #f4f4f9; text-align: center;">
        <p>&copy; 2024 Bunny Vibes | Todos los derechos reservados</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
