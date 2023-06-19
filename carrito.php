<?php
require_once "config/conexion.php";
require_once "config/config.php";
//include 'enviar_email.php';

?>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://smtpjs.com/v3/smtp.js"></script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Carrito de Compras</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" /> -->
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="assets/css/styles.css" rel="stylesheet" />
    <link href="assets/css/estilos.css" rel="stylesheet" />
</head>

<body>
    <!-- Navigation-->
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Inicio</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>
    </div>
    <!-- Header-->
    <header class="bg-dark py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bolder">Carrito</h1>
                <p class="lead fw-normal text-white-50 mb-0">Tus Productos Agregados.</p>
            </div>
        </div>
    </header>
    <section class="py-5">
        <div class="container px-4 px-lg-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Id producto</th>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Sub Total</th>
                                </tr>
                            </thead>
                            <tbody id="tblCarrito">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <h4>Total a Pagar: <span id="total_pagar">0.00</span></h4>
                        <div class="d-grid gap-2">
                            <div id="paypal-button-container"></div>
                            <button class="btn btn-warning" type="button" id="btnVaciar" onClick="redirect()">Vaciar
                                Carrito</button>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="d-grid gap-2">
                            <h4>Introduzca sus datos para finalizar compra</h4>
                            <input type="text" size="15" maxlength="30" placeholder="Nombre" name="nombre" id="nombre">
                            <input type="email" size="15" maxlength="30" placeholder="Correo" name="correo" id="correo">
                            <input type="text" size="15" maxlength="30" placeholder="Direccion" name="direccion"
                                id="direccion">
                            <input type="text" size="15" maxlength="30" placeholder="Tarjeta" name="tarjeta"
                                id="tarjeta">
                            <button onclick="enviarCorreo()" style="display: none;" class="btn btn-warning"
                                type="button" id="btnPagar">Pagar carrito</button>
                        </div>
                    </div>
                </div>


    </section>
    <!-- Footer-->
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Tesji 2023</p>
        </div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script>
        mostrarCarrito();
        function mostrarCarrito() {
            if (localStorage.getItem("productos") != null) {
                let array = JSON.parse(localStorage.getItem('productos'));
                if (array.length > 0) {
                    $.ajax({
                        url: 'ajax.php',
                        type: 'POST',
                        async: true,
                        data: {
                            action: 'buscar',
                            data: array
                        },
                        success: function (response) {
                            console.log(response);
                            const res = JSON.parse(response);
                            let html = '';
                            res.datos.forEach(element => {
                                html += `
                            <tr>
                                <td>${element.id}</td>
                                <td>${element.nombre}</td>
                                <td>${element.precio}</td>
                                <td>1</td>
                                <td>${element.precio}</td>
                            </tr>
                            `;
                            });
                            $('#tblCarrito').html(html);
                            $('#total_pagar').text(res.total);

                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                }
            }
        }


        function redirect() {

            window.location.href = "index.php";
        }


        function enviarCorreo() {
            var nombre = document.getElementById('nombre').value;
            var correo = document.getElementById('correo').value;
            var direccion = document.getElementById('direccion').value;

            $.ajax({
                url: 'enviar_email.php',
                type: 'POST',
                data: {
                    nombre: nombre,
                    correo: correo,
                    direccion: direccion
                },
                success: function (response) {
                    console.log(response);
                    actualizarCantidadProductos(); // Llamada a la función para actualizar la cantidad de productos en la base de datos
                    window.alert("Pago realizado exitosamente");
                    window.location.href = "index.php";
                    localStorage.removeItem("productos");
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }


        // Obtener referencias a los elementos del DOM
        var nombre = document.getElementById('nombre');
        var correo = document.getElementById('correo');
        var tarjeta = document.getElementById('tarjeta');
        var direccion = document.getElementById('direccion');
        var btnPagar = document.getElementById('btnPagar');

        // Agregar un evento de escucha al evento 'input' para verificar cada vez que se cambia el contenido del campo de texto
        nombre.addEventListener('input', validarCampos);
        correo.addEventListener('input', validarCampos);
        tarjeta.addEventListener('input', validarCampos);
        direccion.addEventListener('input', validarCampos);

        // Función para validar los campos y mostrar/ocultar el botón en consecuencia
        function validarCampos() {
            // Verificar si los campos están vacíos
            if (nombre.value === '' || correo.value === '' || tarjeta.value === '' || direccion.value === '') {
                btnPagar.style.display = 'none'; // Ocultar el botón
            } else {
                btnPagar.style.display = 'block'; // Mostrar el botón
            }
        }

        function actualizarCantidadProductos() {
            // Obtener el ID del producto de cada elemento de la tabla
            $('#tblCarrito tr').each(function () {
                var producto_id = $(this).find('td:first-child').text();

                // Petición AJAX para disminuir el stock del producto
                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    data: {
                        action: 'disminuir_stock',
                        producto_id: producto_id
                    },
                    success: function (response) {
                        console.log(response);
                        var data = JSON.parse(response);

                        if (data.status === 'success') {
                            // Stock disminuido exitosamente
                            alert('Stock actualizado');

                            // Realizar otras acciones necesarias después de disminuir el stock

                        } else {
                            // Error al disminuir el stock
                            alert('Error: ' + data.message);
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });

            localStorage.removeItem("productos");
            $('#tblCarrito').html('');
            $('#total_pagar').text('0.00');

        }

    </script>
</body>

</html>