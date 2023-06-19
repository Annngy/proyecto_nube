<?php
require_once "config/conexion.php";
require_once "config/config.php";

// Obtener los datos enviados en la solicitud POST
$productos = $_POST['data'];

if (!empty($productos)) {
    $affectedRows = 0;

    foreach ($productos as $producto) {
        $productoId = $producto['id'];

        // Verificar si hay suficientes productos disponibles antes de realizar el descuento
        $query = mysqli_query($conexion, "SELECT cantidad FROM productos WHERE id = $productoId");
        $result = mysqli_fetch_assoc($query);
        $cantidadDisponible = $result['cantidad'];

        if ($cantidadDisponible > 0) {
            // Realizar el descuento de la cantidad en la base de datos
            $sql = "UPDATE productos SET cantidad = cantidad - 1 WHERE id = $productoId";

            if ($conexion->query($sql) === TRUE) {
                $affectedRows++;
            } else {
                echo "Error: " . $sql . "<br>" . $conexion->error;
            }
        } else {
            echo "No hay suficientes productos disponibles para el producto con ID $productoId";
        }
    }

    if ($affectedRows > 0) {
        echo "Cantidad actualizada para $affectedRows producto(s)";
    } else {
        echo "No se actualizaron las cantidades";
    }
}

$conexion->close();
?>
