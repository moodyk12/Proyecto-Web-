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
                            <a href="#" class="btn btn-rosa">
                                Cesta <span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span>
                            </a>
                            <a href="login.php" class="btn btn-rosa ms-2">Iniciar Sesión</a> 
                        </div>
                    </div>
                </div>
            </header>

            <section>
                <div class="container">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                    <th></th>
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
                                            <td><?php echo MONEDA . number_format($precio_descuento, 2, '.', ','); ?></td>
                                            <td>
                                                <input type="number" min="1" max="10" step="1" value="<?php echo $cantidad; ?>" size="5" id="cantidad_<?php echo $_id; ?>" onchange="actualizaCantidad(this.value, <?php  echo $_id; ?>)">
                                            </td>
                                            <td>
                                                <div id="subtotal_<?php echo $_id; ?>"name="subtotal[]"><?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?></div>
                                            </td>
                                            <td>
                                                <a href="#" id="eliminar" class="btn btn-cesta" data-bs-id="<?php echo $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal">Eliminar</a>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="2">
                                        <p class="h3" id="total"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>

                    <?php if ($lista_cesta != null) { ?>
                        <div class="row">
                            <div class="col-md-4 offset-md-6 d-grid gap-2">
                            <a href="pago.php" class="btn btn-cesta btn-lg">Realizar Pago </a>
                            </div>
                        </div>
                    <?php } ?>
            </section>  
            <!-- //NUEVO A VER SI ME GUSTA SI NO CAMBIO XD  probar cambiar luego sin necesidad del modal -->
                <div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="eliminaModalLabel"><b>Aviso</b></h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Eliminara el producto de la cesta
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-cesta" data-bs-dismiss="modal">Cerrar</button>
                                <button id="btn-elimina" type="button" class="btn-cesta" onclick="eliminar()">Eliminar</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                    <script src="js/jquery.min.js"></script>
                    <script src="js/bootstrap.min.js"></script>
                    <script src="js/app.js"></script>
                    
                <script>
                    function actualizaCantidad(cantidad, id) {
                        let url = 'clases/Actu_cesta.php';
                        let formData = new FormData();
                        formData.append('action', 'agregar');
                        formData.append('id', id);
                        formData.append('cantidad', cantidad);

                        fetch(url, {
                            method: 'POST',
                            body: formData,
                            mode: 'cors'
                        }).then(response => response.json())
                        .then(data => {
                            if (data.ok) {
                                let divsubtotal = document.getElementById('subtotal_' + id);
                                divsubtotal.innerHTML = data.sub

                                //nuevo probando
                                let total = 0.00
                                let list = document.getElementsByName('subtotal[]')
                            
                                for(let i = 0; i < list.length; i++){
                                    total +=parseFloat(list[i].innerHTML.replace(/[$,]/g, ''))
                                }
                                total = new Intl.NumberFormat('en-US', {
                                    minimumFractionDigits: 2
                                }).format(total)
                                document.getElementById('total').innerHTML = '<?php echo MONEDA; ?>' + total;
                            }
                        })
                    }
                </script>
        </body>
</html>