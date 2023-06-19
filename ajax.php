<?php
require_once "config/conexion.php";

if (isset($_POST) && $_POST['action'] == 'buscar') {
    $array['datos'] = array();
    $total = 0;

    foreach ($_POST['data'] as $item) {
        $id = $item['id'];
        $query = mysqli_query($conexion, "SELECT * FROM productos WHERE id = $id");
        $result = mysqli_fetch_assoc($query);

        $data['id'] = $result['id'];
        $data['precio'] = $result['precio_rebajado'];
        $data['nombre'] = $result['nombre'];
        $total += $result['precio_rebajado'];
        array_push($array['datos'], $data);
    }

    $array['total'] = $total;
    echo json_encode($array);
    die();
}


if (isset($_POST) && $_POST['action'] == 'disminuir_stock') {
    $producto_id = $_POST['producto_id'];

    // Obtener información del producto
    $query = mysqli_query($conexion, "SELECT * FROM productos WHERE id = $producto_id");
    $result = mysqli_fetch_assoc($query);
    

    if ($result) {
        // Realizar la disminución del stock
        $nuevo_stock = $result['cantidad'] - 1;

        // Actualizar el stock en la base de datos
        mysqli_query($conexion, "UPDATE productos SET cantidad = $nuevo_stock WHERE id = $producto_id");

        // Preparar la respuesta
        $response = array(
            'status' => 'success',
            'stock' => $nuevo_stock
        );
    } else {
        // El producto no existe
        $response = array(
            'status' => 'error',
            'message' => 'El producto no existe.'
        );
    }

    echo json_encode($response);
    die();
}







?>
