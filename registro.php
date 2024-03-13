<?php
require_once("db/conexion.php");
$base = new Database();
$conexion = $base->conectar();

require 'vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

if (isset($_POST["btn-guardar"])) {
    // Obtener datos del formulario
    $id_lote = $_POST['id_lote'];
    $barrio = $_POST['barrio'];
    $frente = $_POST['frente'];
    $ancho = $_POST['ancho'];  
    $dueño = $_POST['dueño'];  

    // Validar campos requeridos
    $campos_requeridos = ['id_lote', 'barrio', 'frente', 'ancho', 'dueño'];
    $campos_vacios = [];
    foreach ($campos_requeridos as $campo) {
        if (empty($_POST[$campo])) {
            $campos_vacios[] = $campo;
        }
    }

    if (!empty($campos_vacios)) {
        echo '<script>alert("Los siguientes campos son obligatorios: ' . implode(', ', $campos_vacios) . '");</script>';
    } else {
        // Utilizar consultas preparadas para mejorar la seguridad
        $consulta = $conexion->prepare("SELECT * FROM lote WHERE id_lote = ?");
        $consulta->execute([$id_lote]);
        $num_filas = $consulta->rowCount();

        if ($num_filas > 0) {
            echo '<script>alert("El id_lote ya está registrado.");</script>';
        } else {
            $codigo_de_barras = uniqid();

            $generator = new BarcodeGeneratorPNG();
            $codigo_imagen = $generator->getBarcode($codigo_de_barras, $generator::TYPE_CODE_128);

            // Guardar el código de barras en un archivo
            file_put_contents(__DIR__ . '/images/' . $codigo_de_barras . '.png', $codigo_imagen);

            // Insertar el nuevo lote en la base de datos
            $consulta_insertar = $conexion->prepare("INSERT INTO lote (id_lote, barrio, frente, ancho, dueño, cod_barras) VALUES (?, ?, ?, ?, ?, ?)");
            $consulta_insertar->execute([$id_lote, $barrio, $frente, $ancho, $dueño, $codigo_de_barras]);

            echo '<script>alert ("Registro exitoso, gracias");</script>';
            echo '<script>window.location= "registro.php."</script>';
            exit();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de lote</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #343a40;
            /* Fondo oscuro */
            color: #adb5bd;
            /* Color del texto */
            padding-top: 50px;
        }

        .form-container {
            background-color: #495057;
            /* Fondo gris oscuro */
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
            /* Sombra ligera */
            max-width: 400px;
            margin: 0 auto;
            /* Centrar en la página */
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            /* Color del botón principal */
            border-color: #007bff;
            color: #fff;
            /* Color del texto del botón */
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            display: inline-block;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            /* Color del botón principal al pasar el mouse */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <form method="POST" action="">
                        <h2 class="mb-4">Registro de lote</h2>
                        <div class="form-group">
                            <label for="id_lote">numero de lote:</label>
                            <input type="number" class="form-control" id="id_lote" name="id_lote" required>
                        </div>

                        <div class="form-group">
                            <label for="barrio">Barrio:</label>
                            <input type="text" class="form-control" name="barrio" required>
                        </div>
                        <div class="form-group">
                            <label for="frente">Frente:</label>
                            <input type="number" class="form-control" name="frente" required>
                        </div>
                        <div class="form-group">
                            <label for="ancho">Ancho:</label>
                            <input type="number" class="form-control" name="ancho" required>
                        </div>
                        <div class="form-group">
                            <label for="dueño">Dueño:</label>
                            <input type="text" class="form-control" name="dueño" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" name="btn-guardar">Guardar</button>
                            <a href="registro.php" class="btn btn-dark">Volver</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
