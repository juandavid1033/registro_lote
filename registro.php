<?php
require_once("db/conexion.php");
$base = new Database();
$conexion = $base->conectar();

if (isset($_POST["btn-guardar"])) {
    $documento = $_POST['documento'];
    $nombres = $_POST['nombres'];
    $raw_password = $_POST['contrasena'];  // Contraseña sin cifrar

    // Encriptar la contraseña utilizando password_hash
    $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

    $id_rol = $_POST['id_rol'];
    $id_tipo_documento = $_POST['id_tipo_documento'];

    // Puedes definir el valor de id_estados directamente o recuperarlo de alguna lógica específica
    $id_estados = 1; // Por ejemplo, asumamos que 1 es un estado válido

    // Utilizamos consultas preparadas para mejorar la seguridad
    $validar = $conexion->prepare("SELECT * FROM usuario WHERE documento = ?");
    $validar->execute([$documento]);
    $fila1 = $validar->fetchAll(PDO::FETCH_ASSOC);

    if ($fila1) {
        echo '<script>alert("El documento ya está registrado.");</script>';
    } else {
        // Utilizamos consultas preparadas para mejorar la seguridad
        $consulta3 = $conexion->prepare("INSERT INTO usuario (documento, nombres, contrasena, id_rol, id_tipo_documento, id_estados) VALUES (?, ?, ?, ?, ?, ?)");
        $consulta3->execute([$documento, $nombres, $hashed_password, $id_rol, $id_tipo_documento, $id_estados]);

        echo '<script>alert("Registro exitoso, gracias");</script>';
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa; /* Fondo claro */
            color: #495057; /* Color del texto */
            padding-top: 50px;
        }

        .form-container {
            background-color: #ffffff; /* Fondo blanco */
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1); /* Sombra ligera */
        }

        select, input {
            border: 2px solid #007bff; /* Color del borde */
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            color: #495057; /* Color del texto del input */
            background-color: #fff; /* Color del fondo del input */
        }

        label {
            margin-bottom: 0;
        }

        .btn-primary {
            background-color: #007bff; /* Color del botón principal */
            border-color: #007bff;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <form method="POST" action="">
                        <h2 class="mb-4">Registro de Usuario</h2>
                        <div class="form-group">
                            <label for="documento">Documento de Identidad:</label>
                            <input type="text" class="form-control" name="documento" required>
                        </div>
                        <div class="form-group">
                            <label for="nombres">Nombre:</label>
                            <input type="text" class="form-control" name="nombres" required>
                        </div>
                        <div class="form-group">
                            <label for="contrasena">Contraseña:</label>
                            <input type="password" class="form-control" name="contrasena" required>
                        </div>
                        <div class="form-group">
                            <label for="id_rol">Rol:</label>
                            <select class="form-control" name="id_rol">
                                <option value="1">admnistrador</option>
                                <option value="2">vigilante</option>
                                <option value="3">aprendiz</option>
                                <option value="4">visitante</option>
                                <option value="4">superadministtrador</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_tipo_documento">Tipo de Documento:</label>
                            <select class="form-control" name="id_tipo_documento">
                                <option value="1">CC</option>
                                <option value="2">TI</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" name="btn-guardar">Guardar</button>
                        <a href="index.html" class="btn btn-dark">Volver</a>
                        <p class="mt-3">¿Ya tienes una cuenta? <a class="ingresar" href="login.php">Ingresar</a></p>
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
