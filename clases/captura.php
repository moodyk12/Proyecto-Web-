<?php

require '../config/config.php';
require '../config/database.php';

// Establecer la zona horaria de Nicaragua
date_default_timezone_set('America/Managua');

$db = new Database();
$con = $db->conectar();

$json = file_get_contents('php://input');
$datos = json_decode($json, true);

print_r($datos);
if(is_array($datos)){

    $id_cliente = $_SESSION['user_cliente'];

    $sql = $con->prepare("SELECT email FROM clientes WHERE id=? AND estatus=1");
    $sql->execute([$id_cliente]);
    $row_cliente = $sql->fetch(PDO::FETCH_ASSOC);


    $id_transaccion = $datos['detalles']['id'];
    $total = $datos['detalles'] ['purchase_units'][0]['amount'] ['value'];
    $status = $datos['detalles']['status'];
    $fecha = $datos ['detalles'] ['update_time'];
    
    // Convertir la fecha y hora a formato Y-m-d H:i:s usando la zona horaria correcta
    $fecha_nueva = date('Y-m-d H:i:s', strtotime($fecha));
    $email = $row_cliente['email'];
    
    // Si la fecha está en formato UTC y deseas ajustar la hora local:
    // $fecha_nueva = date('Y-m-d H:i:s', strtotime($fecha) - 6 * 3600); // Ajuste UTC-6
    // $email = $datos['detalles'] ['payer'] ['email_address'];
    // $id_cliente = $datos['detalles']['payer']['payer_id'];

    // Insertar la compra en la base de datos
    $sql = $con->prepare("INSERT INTO compra(id_transaccion, fecha, status, email, id_cliente, total) VALUES (?,?,?,?,?,?)");

    $sql->execute([$id_transaccion, $fecha_nueva, $status, $email, $id_cliente, $total]);
    $id = $con->lastInsertId();

    if( $id > 0){
        // Procesar los productos de la cesta
        $producto = isset($_SESSION['cesta']['productos']) ? $_SESSION['cesta']['productos'] : null;
        if ($producto != null) {
            foreach ($producto as $clave => $cantidad) {

                // Obtener el producto de la base de datos
                $sql = $con->prepare("SELECT id, nombre, precio, descuento FROM productos WHERE id=? AND activo=1");
                $sql->execute([$clave]);
                $row_prod = $sql->fetch(PDO::FETCH_ASSOC);

                $precio = $row_prod['precio'];
                $descuento = $row_prod['descuento'];
                $precio_descuento = $precio * (1 - ($descuento / 100)); 

                // Insertar el detalle de la compra
                $sql_insert = $con->prepare("INSERT INTO detalle_compra(id_compra, id_producto, nombre, precio, cantidad) VALUES (?,?,?,?,?)");
                $sql_insert->execute([$id, $clave, $row_prod['nombre'], $precio_descuento, $cantidad]);

            }
            // Incluir el archivo de correo si es necesario
            include 'correo.php';
        }
        // Limpiar la cesta
        unset($_SESSION['cesta']);
    }

}
?>