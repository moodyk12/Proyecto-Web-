<?php
require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

// NUEVO POR SI DA ERROR
$producto = isset($_SESSION['cesta']['productos']) ? $_SESSION['cesta']['productos'] : null;
$lista_cesta = array();
if ($producto != null) {
    foreach ($producto as $clave => $cantidad) {
        $sql = $con->prepare("SELECT id, nombre, precio, descuento FROM productos WHERE id=? AND activo=1");
        $sql->execute([$clave]);
        $producto_data = $sql->fetch(PDO::FETCH_ASSOC);
        
        if ($producto_data) {
            $producto_data['cantidad'] = $cantidad; // Agregar cantidad al producto
            $lista_cesta[] = $producto_data;
        }
    }
}else{
    header("Location: index.php");
    exit;
}
// TERMINA 
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
                            <a href="#" class="btn btn-rosa me-3">
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
                        <div class="col-6">
                            <h4>Detalles de Pago</h4>
                            <div id="paypal-button-container"></div>
                        </div>
                        <div class="col-6">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $total = 0; 
                                        if ($lista_cesta == null || empty($lista_cesta)) {
                                            echo '<tr><td colspan="5" class="text-center"><b>Cesta Vacía</b></td></tr>';
                                        } else {
                                            foreach ($lista_cesta as $producto) {
                                                $_id = $producto['id'];
                                                $nombre = $producto['nombre'];
                                                $precio = $producto['precio'];
                                                $descuento = $producto['descuento'];
                                                $cantidad = $producto['cantidad']; 
                                                $precio_descuento = $precio * (1 - ($descuento / 100)); 
                                                $subtotal = $cantidad * $precio_descuento; 
                                                $total += $subtotal; ?>
                                                <tr>
                                                    <td><?php echo $nombre; ?></td>
                                                    <td>
                                                        <div id="subtotal_<?php echo $_id; ?>"name="subtotal[]"><?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?></div>
                                                    </td>
                                                </tr>
                                            <?php }
                                        } ?>
                                        <tr>
                                            <td colspan="2">
                                                <p class="h3 text-end" id="total"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>  
                
                    <script src="js/jquery.min.js"></script>
                    <script src="js/bootstrap.bundle.min.js"></script>
                    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&currency=<?php echo CURRENCY; ?>"></script>
                    <script>
                        paypal.Buttons({
                            style: {
                                color: 'blue',  
                                shape: 'pill',  // Mantiene la forma 'pill'
                                label: 'pay'
                            },
                            createOrder: function(data, actions) {
                                return actions.order.create({
                                    purchase_units: [{
                                        amount: {
                                            value: <?php echo $total;?> 
                                        }
                                    }]
                                });
                            },
                            onApprove: function(data, actions){
                                let URL = 'clases/captura.php'
                                actions.order.capture().then(function(detalles){
                                    console.log(detalles)
                                    return fetch(URL,{
                                        method: 'post',
                                        headers:{
                                            'content-type': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            detalles: detalles
                                        })
                                    }).then(function(response){;
                                        window.location.href = "recibido.php?key=" + detalles['id'];
                                    })
                                
                                });

                            },
                            onCancel: function(data){
                                alert("Pago Cancelado")
                                console.log(data)
                            }
                    
                        }).render('#paypal-button-container');

                    </script>
                
        </body>
</html>