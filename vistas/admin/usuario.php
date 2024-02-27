<?php
require_once("../../db/conexion.php");

// Conectar a la base de datos
$db = new Database();
$conectar = $db->conectar();

// Obtener la lista de usuarios registrados
$lista_usuarios = $conectar->prepare("SELECT * FROM usuario");
$lista_usuarios->execute();
$listas_usuarios = $lista_usuarios->fetchAll(PDO::FETCH_ASSOC);

// Procesar el formulario de creación de usuarios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $documento = $_POST["documento"];
    $nombres = $_POST["nombres"];
    $nit_empresa = $_POST["nit_empresa"];
    $contrasena = $_POST["contrasena"];
    $codigo_barras = $_POST["codigo_barras"];
    $id_rol = $_POST["id_rol"];
    $id_tipo_documento = $_POST["id_tipo_documento"];
    $id_estados = $_POST["id_estados"];

    // Insertar el nuevo usuario en la base de datos
    $insertar_usuario = $conectar->prepare("INSERT INTO usuario (documento, nombres, nit_empresa, contrasena, codigo_barras, id_rol, id_tipo_documento, id_estados) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insertar_usuario->execute([$documento, $nombres, $nit_empresa, $contrasena, $codigo_barras, $id_rol, $id_tipo_documento, $id_estados]);

    // Recargar la página para mostrar la lista actualizada
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Creación de Usuarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        form {
            margin-top: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        button {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Lista de Usuarios Registrados</h2>
    <table>
        <thead>
            <tr>
                <th>Documento</th>
                <th>Nombres</th>
                <th>NIT de la Empresa</th>
                <th>Contraseña</th>
                <th>Código de Barras</th>
                <th>ID de Rol</th>
                <th>ID de Tipo de Documento</th>
                <th>ID de Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listas_usuarios as $usuario) { ?>
                <tr>
                    <td><?= $usuario["documento"] ?></td>
                    <td><?= $usuario["nombres"] ?></td>
                    <td><?= $usuario["nit_empresa"] ?></td>
                    <td><?= $usuario["contrasena"] ?></td>
                    <td><?= $usuario["codigo_barras"] ?></td>
                    <td><?= $usuario["id_rol"] ?></td>
                    <td><?= $usuario["id_tipo_documento"] ?></td>
                    <td><?= $usuario["id_estados"] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>


    <a href="index.php">Volver</a>
</body>
</html>
